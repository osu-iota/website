<?php

namespace OSU\IOTA\Util;

class Security {
    public static function generateSecureUniqueId($length = 13) {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        try {
            for ($i = 0; $i < $length; $i++) {
                $token .= $codeAlphabet[\random_int(0, $max - 1)];
            }
        } catch (\Exception $e) {
            return null;
        }

        return $token;
    }
}
