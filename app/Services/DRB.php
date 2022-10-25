<?php

namespace App\Services;

use App\Models\ExpectedItem;
use App\Models\Machine;
use App\Models\MachineGameItem;
use App\Models\RealityItem;
use Exception;

class DRB
{
    
    public function receiveRequest($machineId, $decodedMessage)
    {
        $action = $decodedMessage->action;
        $command = $decodedMessage->command;
        $data = empty($decodedMessage->data) ? "" : $decodedMessage->data;
        echo $command . "\n";

        if ($action === "setting") {
            if ($command === "connect") {
                return $this->machineConnect($machineId);
            }
            if ($command === "disconnect") {
                return $this->machineDisconnect($machineId);
            }
        }

        if ($action === "request") {
            if ($command === "new Game") {
                return $this->newGameRequest($machineId);
            }
        }

        if ($action === "transfer") {
            if ($command === "end Game") {
                return $this->endGameRequest($machineId, $data);
            }
        }
    }

    public function machineConnect($machineId)
    {
        $machine = Machine::find($machineId);

        if (!empty($machine)) {
            $machine->M_online_status = true;
            $machine->save();
        }

        return NULL;
    }

    public function machineDisconnect($machineId)
    {
        $machine = Machine::find($machineId);

        if (!empty($machine)) {
            $machine->M_online_status = false;
            $machine->save();
        }

        return NULL;
    }

    public function newGameRequest($machineId, $gameDifficulty = 7, $maxValue = 4, $blinkTime = 500)
    {
        $prevGameItem = MachineGameItem::where("M_id", $machineId)->orderBy('MGI_sequence', 'desc')->first();
        $machineGameItem = new MachineGameItem;
        $machineGameItem->MGI_sequence = 0;

        if (!empty($prevGameItem)) {

            $machineGameItem->MGI_sequence = intval($prevGameItem['MGI_sequence']) + 1;
        }

        $machineGameItem->M_id = $machineId;
        $machineGameItem->MGI_start_game_time = date("Y-m-d H:i:s");
        $machineGameItem->MGI_end_game_time = date("Y-m-d H:i:s");

        $machineGameItem->save();

        $data = [];
        for ($i = 0; $i < $gameDifficulty; $i++) {
            $expectedItem = new ExpectedItem;
            $realityItem = new RealityItem;

            $expectedItem->EI_data = $data[$i] = rand(1, $maxValue);
            $realityItem->MGI_sequence = $expectedItem->MGI_sequence = $machineGameItem->MGI_sequence;
            $realityItem->M_id = $expectedItem->M_id = $machineId;
            $realityItem->RI_sequence = $expectedItem->EI_sequence = $i;

            $expectedItem->save();
            $realityItem->save();
        }
        $data['size'] = $gameDifficulty;
        $data['blinkTime'] = $blinkTime;

        return [
            "action" => "response",
            "command" => "new Game",
            "data" => $data,
            "from" => "server",
        ];
    }

    public function endGameRequest($machineId, $data)
    {
        $lastGameItem = MachineGameItem::where("M_id", $machineId)->orderBy('MGI_sequence', 'desc')->first();
        $startTime = $lastGameItem->MGI_start_game_time;
        $endTime = $lastGameItem->MGI_end_game_time;

        if (strtotime($startTime) !== strtotime($endTime)) {
            return NULL;
        }

        $lastGameItem->MGI_end_game_time = date("Y-m-d H:i:s");
        $lastGameItem->save();

        for ($i = 0; $i < count($data); $i++) {
            $realityItem = RealityItem::where('M_id', $machineId)
                ->where('MGI_sequence', $lastGameItem->MGI_sequence)
                ->where('RI_sequence', $i)
                ->first();

            $realityItem->RI_data = $data[$i] === 0 ? NULL : $data[$i];
            $realityItem->save();
        }
    }
}
