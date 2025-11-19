<?php



namespace Models;

class DeviceInfoModel{

   public function getAvailableDevices($db): array
    {
        $rows = $db->countMessagesPerDevice();

        if ($rows === false) {
            return [];
        }

        return $rows;
    }

     public function getLatestForDevice(string $deviceId, $db): array|false
    {
        return $db->getLatestMessageForDevice($deviceId);
    }
      





}


?>