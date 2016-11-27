<?php

namespace App;

class GiantBombHelper
{
    const BASE_API_URL = 'https://www.giantbomb.com/api/';

    const REGION_UNITED_STATES = 1;
    const REGION_UNITED_KINGDOM = 2;
    const REGION_JAPAN = 6;
    const REGION_AUSTRALIA = 11;

    const PLATFORM_AMIGA = 1;
    const PLATFORM_GAME_BOY = 3;
    const PLATFORM_GAME_BOY_ADVANCE = 4;
    const PLATFORM_GAME_GEAR = 5;
    const PLATFORM_GENESIS = 6;
    const PLATFORM_ATARI_LYNX = 7;
    const PLATFORM_SEGA_MASTER_SYSTEM = 8;
    const PLATFORM_SUPER_NINTENDO_ENTERTAINMENT_SYSTEM = 9;
    const PLATFORM_AMSTRAD_CPC = 11;
    const PLATFORM_APPLE_II = 12;
    const PLATFORM_ATARI_ST = 13;
    const PLATFORM_COMMODORE_64 = 14;
    const PLATFORM_MSX = 15;
    const PLATFORM_ZX_SPECTRUM = 16;
    const PLATFORM_MAC = 17;
    const PLATFORM_PLAYSTATION_PORTABLE = 18;
    const PLATFORM_PLAYSTATION_2 = 19;
    const PLATFORM_XBOX_360 = 20;
    const PLATFORM_NINTENDO_ENTERTAINMENT_SYSTEM = 21;
    const PLATFORM_PLAYSTATION = 22;
    const PLATFORM_GAMECUBE = 23;
    const PLATFORM_ATARI_8_BIT = 24;
    const PLATFORM_NEO_GEO = 25;
    const PLATFORM_3DO = 26;
    const PLATFORM_CD_I = 27;
    const PLATFORM_JAGUAR = 28;
    const PLATFORM_SEGA_CD = 29;
    const PLATFORM_VIC_20 = 30;
    const PLATFORM_SEGA_32X = 31;
    const PLATFORM_XBOX = 32;
    const PLATFORM_N_GAGE = 34;
    const PLATFORM_PLAYSTATION_3 = 35;
    const PLATFORM_WII = 36;
    const PLATFORM_DREAMCAST = 37;
    const PLATFORM_APPLE_IIGS = 38;
    const PLATFORM_AMIGA_CD32 = 39;
    const PLATFORM_ATARI_2600 = 40;
    const PLATFORM_SATURN = 42;
    const PLATFORM_NINTENDO_64 = 43;
    const PLATFORM_COLECOVISION = 47;
    const PLATFORM_TI_99_4A = 48;
    const PLATFORM_INTELLIVISION = 51;
    const PLATFORM_NINTENDO_DS = 52;
    const PLATFORM_TURBOGRAFX_CD = 53;
    const PLATFORM_WONDERSWAN_COLOR = 54;
    const PLATFORM_TURBOGRAFX_16 = 55;
    const PLATFORM_GAME_BOY_COLOR = 57;
    const PLATFORM_COMMODORE_128 = 58;
    const PLATFORM_NEO_GEO_CD = 59;
    const PLATFORM_ODYSSEY_2 = 60;
    const PLATFORM_DRAGON_32_64 = 61;
    const PLATFORM_COMMODORE_PET_CBM = 62;
    const PLATFORM_TRS_80 = 63;
    const PLATFORM_ZODIAC = 64;
    const PLATFORM_WONDERSWAN = 65;
    const PLATFORM_CHANNEL_F = 66;
    const PLATFORM_ATARI_5200 = 67;
    const PLATFORM_TRS_80_COCO = 68;
    const PLATFORM_ATARI_7800 = 70;
    const PLATFORM_IPOD = 72;
    const PLATFORM_ODYSSEY = 74;
    const PLATFORM_PC_FX = 75;
    const PLATFORM_VECTREX = 76;
    const PLATFORM_GAMECOM = 77;
    const PLATFORM_GIZMONDO = 78;
    const PLATFORM_VIRTUAL_BOY = 79;
    const PLATFORM_NEO_GEO_POCKET = 80;
    const PLATFORM_NEO_GEO_POCKET_COLOR = 81;
    const PLATFORM_VSMILE = 82;
    const PLATFORM_PINBALL = 83;
    const PLATFORM_ARCADE = 84;
    const PLATFORM_NUON = 85;
    const PLATFORM_XBOX_360_GAMES_STORE = 86;
    const PLATFORM_WII_SHOP = 87;
    const PLATFORM_PLAYSTATION_NETWORK_PS3 = 88;
    const PLATFORM_LEAPSTER = 89;
    const PLATFORM_MICROVISION = 90;
    const PLATFORM_FAMICOM_DISK_SYSTEM = 91;
    const PLATFORM_PIONEER_LASERACTIVE = 92;
    const PLATFORM_ADVENTURE_VISION = 93;
    const PLATFORM_PC = 94;
    const PLATFORM_SHARP_X68000 = 95;
    const PLATFORM_IPHONE = 96;
    const PLATFORM_SATELLAVIEW = 98;
    const PLATFORM_ARCADIA_2001 = 99;
    const PLATFORM_AQUARIUS = 100;
    const PLATFORM_NINTENDO_64DD = 101;
    const PLATFORM_PIPPIN = 102;
    const PLATFORM_R_ZONE = 103;
    const PLATFORM_HYPERSCAN = 104;
    const PLATFORM_GAME_WAVE = 105;
    const PLATFORM_DSIWARE = 106;
    const PLATFORM_RDI_HALCYON = 107;
    const PLATFORM_FM_TOWNS = 108;
    const PLATFORM_NEC_PC_8801 = 109;
    const PLATFORM_BBC_MICRO = 110;
    const PLATFORM_PLATO = 111;
    const PLATFORM_NEC_PC_9801 = 112;
    const PLATFORM_SHARP_X1 = 113;
    const PLATFORM_FM_7 = 114;

    public static function makeRequest($path, $options)
    {
        $query = [
            'api_key' => config('giant_bomb.api_key'),
            'format' => 'json',
        ];

        $query = array_merge($query, $options);

        $client = new \GuzzleHttp\Client();

        $res = $client->request('GET', self::BASE_API_URL . $path, [
            'query' => $query,
        ]);

        return $res;
    }

    public static function searchGame($name, $platforms = null)
    {
        $res = self::makeRequest('search/', [
            'query' => $name,
            'resources' => 'game',
        ]);

        $games = json_decode($res->getBody(), true)['results'];

        usort($games, function ($game1, $game2) use ($name) {
            if (strtolower($game1['name']) === strtolower($game2['name'])) {
                return 0;
            }

            if (strtolower($game2['name']) === strtolower($name)) {
                return 1;
            }
        });

        if (!empty($platforms)) {
            foreach ($games as $index => &$game) {
                if (!$game['platforms']) {
                    unset($games[$index]);
                    $games = array_values($games);
                    continue;
                }

                $game_platforms = array_column($game['platforms'], 'id');

                if (!array_intersect($platforms, $game_platforms)) {
                    unset($games[$index]);
                    $games = array_values($games);
                    continue;
                }
            }
        }

        return $games;
    }

    public static function getReleasesByGameId($game_id, $platforms = null, $regions = null)
    {
        $filter = 'game:' . $game_id;

        if (!empty($platforms)) {
            $filter .= ',platform:' . implode($platforms, '|');
        }

        if (!empty($regions)) {
            $filter .= ',region:' . implode($regions, '|');
        }

        $res = self::makeRequest('releases/', [
            'field_list' => 'image,id,site_detail_url',
            'filter' => $filter,
        ]);

        return json_decode($res->getBody(), true)['results'];
    }

    public static function getGameImage($name, $platforms = null, $regions = null)
    {
        if (!is_array($platforms)) {
            $platforms = [$platforms];
        }

        if (!is_array($regions)) {
            $regions = [$regions];
        }

        $games = self::searchGame($name, $platforms);

        if (count($games) >= 1) {
            $game = $games[0];
        } else {
            throw new \Exception('Unable to find game');
        }

        $game_image = $game['image'];

        if (!empty($platforms)) {
            $releases = self::getReleasesByGameId($game['id'], $platforms, $regions);

            if (!empty($releases[0]) && $releases[0]['image']) {
                $game_image = $releases[0]['image'];
            }
        }

        return $game_image;
    }
}
