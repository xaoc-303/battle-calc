<?php

return array(

    /**
     * init - инициатива
     * type - наземный | воздушный
     * bio - юнит биологический | техника
     * shield - очки щита
     * armor - очки брони
     * hp - очки здоровья
     * mround - задержка атаки (магия)
     * cool - задержка атаки (земля|воздух)
     * attack_ter - атака по земле
     * attack_air - атака по воздуху
     * attack_magic - на сколько юнитов распространяется магическая атака
     * magic1, magic2, magic3 - магия
    */

    // name, init, type, bio, shield, armor, hp, mround, cool, attack_ter, attack_air, attack_magic, magic1, magic2, magic3

    1 => [
        [
            'id' => 101,
            'race_id' => 1,
            'init' => 1,
            'type' => 0, 'bio' => 1,
            'shield' => 20, 'armor' => 10, 'hp' => 20,
            'mround' => 0, 'cool' => 22,
            'attack_ter' => 5, 'attack_air' => 0, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null,
        ],
        [
            'id' => 102,
            'race_id' => 1,
            'init' => 1,
            'type' => 0, 'bio' => 1,
            'shield' => 80, 'armor' => 20, 'hp' => 80,
            'mround' => 0, 'cool' => 22,
            'attack_ter' => 16, 'attack_air' => 0, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 103,
            'race_id' => 1,
            'init' => 4,
            'type' => 0, 'bio' => 0,
            'shield' => 80, 'armor' => 20, 'hp' => 100,
            'mround' => 0, 'cool' => 30,
            'attack_ter' => 20, 'attack_air' => 20, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 104,
            'race_id' => 1,
            'init' => 10,
            'type' => 0, 'bio' => 1,
            'shield' => 40, 'armor' => 10, 'hp' => 40,
            'mround' => 1, 'cool' => 24,
            'attack_ter' => 0, 'attack_air' => 0, 'attack_magic' => 1,
            'magic1' => 'Phantom', 'magic2' => 'Storm', 'magic3' => null
        ],
        [
            'id' => 105,
            'race_id' => 1,
            'init' => 1,
            'type' => 0, 'bio' => 1,
            'shield' => 40, 'armor' => 20, 'hp' => 80,
            'mround' => 0, 'cool' => 30,
            'attack_ter' => 40, 'attack_air' => 0, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 106,
            'race_id' => 1,
            'init' => 2,
            'type' => 0, 'bio' => 1,
            'shield' => 350, 'armor' => 10, 'hp' => 10,
            'mround' => 0, 'cool' => 20,
            'attack_ter' => 30, 'attack_air' => 30, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 107,
            'race_id' => 1,
            'init' => 10,
            'type' => 0, 'bio' => 1,
            'shield' => 200, 'armor' => 20, 'hp' => 25,
            'mround' => 7, 'cool' => 24,
            'attack_ter' => 0, 'attack_air' => 0, 'attack_magic' => 3,
            'magic1' => 'MindControl', 'magic2' => 'LockP', 'magic3' => null
        ],
        [
            'id' => 108,
            'race_id' => 1,
            'init' => 8,
            'type' => 0, 'bio' => 0,
            'shield' => 80, 'armor' => 10, 'hp' => 100,
            'mround' => 5, 'cool' => 60,
            'attack_ter' => 0, 'attack_air' => 0, 'attack_magic' => 0,
            'magic1' => 'Scarab', 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 109,
            'race_id' => 1,
            'init' => 0,
            'type' => 1, 'bio' => 0,
            'shield' => 20, 'armor' => 10, 'hp' => 40,
            'mround' => 0, 'cool' => 24,
            'attack_ter' => 0, 'attack_air' => 0, 'attack_magic' => 10,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 110,
            'race_id' => 1,
            'init' => 0,
            'type' => 1, 'bio' => 0,
            'shield' => 60, 'armor' => 20, 'hp' => 80,
            'mround' => 0, 'cool' => 24,
            'attack_ter' => 0, 'attack_air' => 0, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 111,
            'race_id' => 1,
            'init' => 5,
            'type' => 1, 'bio' => 0,
            'shield' => 80, 'armor' => 20, 'hp' => 100,
            'mround' => 2, 'cool' => 8,
            'attack_ter' => 0, 'attack_air' => 5, 'attack_magic' => 10,
            'magic1' => 'FogP', 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 112,
            'race_id' => 1,
            'init' => 4,
            'type' => 1, 'bio' => 0,
            'shield' => 100, 'armor' => 10, 'hp' => 150,
            'mround' => 0, 'cool' => 22,
            'attack_ter' => 8, 'attack_air' => 28, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 113,
            'race_id' => 1,
            'init' => 8,
            'type' => 1, 'bio' => 0,
            'shield' => 150, 'armor' => 50, 'hp' => 300,
            'mround' => 0, 'cool' => 24,
            'attack_ter' => 36, 'attack_air' => 36, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 114,
            'race_id' => 1,
            'init' => 5,
            'type' => 1, 'bio' => 0,
            'shield' => 150, 'armor' => 20, 'hp' => 200,
            'mround' => 0, 'cool' => 45,
            'attack_ter' => 10, 'attack_air' => 10, 'attack_magic' => 10,
            'magic1' => 'LockP', 'magic2' => 'Jump', 'magic3' => null
        ]
    ],
    2 => [
        [
            'id' => 201,
            'race_id' => 2,
            'init' => 1,
            'type' => 0, 'bio' => 0,
            'shield' => 0, 'armor' => 10, 'hp' => 60,
            'mround' => 0, 'cool' => 15,
            'attack_ter' => 5, 'attack_air' => 0, 'attack_magic' => 0,
            'magic1' => 'Remont', 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 202,
            'race_id' => 2,
            'init' => 4,
            'type' => 0, 'bio' => 1,
            'shield' => 0, 'armor' => 10, 'hp' => 40,
            'mround' => 2, 'cool' => 15,
            'attack_ter' => 6, 'attack_air' => 6, 'attack_magic' => 0,
            'magic1' => 'Steam', 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 203,
            'race_id' => 2,
            'init' => 1,
            'type' => 0, 'bio' => 1,
            'shield' => 0, 'armor' => 20, 'hp' => 50,
            'mround' => 0, 'cool' => 22,
            'attack_ter' => 16, 'attack_air' => 0, 'attack_magic' => 0,
            'magic1' => 'Steam', 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 204,
            'race_id' => 2,
            'init' => 7,
            'type' => 0, 'bio' => 1,
            'shield' => 0, 'armor' => 10, 'hp' => 45,
            'mround' => 1, 'cool' => 22,
            'attack_ter' => 10, 'attack_air' => 10, 'attack_magic' => 0,
            'magic1' => 'LockT', 'magic2' => 'Nuclear', 'magic3' => null
        ],
        [
            'id' => 205,
            'race_id' => 2,
            'init' => 10,
            'type' => 0, 'bio' => 1,
            'shield' => 0, 'armor' => 20, 'hp' => 60,
            'mround' => 2, 'cool' => 24,
            'attack_ter' => 0, 'attack_air' => 0, 'attack_magic' => 5,
            'magic1' => 'Medicine', 'magic2' => 'Blind', 'magic3' => null
        ],
        [
            'id' => 206,
            'race_id' => 2,
            'init' => 5,
            'type' => 0, 'bio' => 0,
            'shield' => 0, 'armor' => 10, 'hp' => 80,
            'mround' => 0, 'cool' => 30,
            'attack_ter' => 20, 'attack_air' => 0, 'attack_magic' => 0,
            'magic1' => 'Mines', 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 207,
            'race_id' => 2,
            'init' => 5,
            'type' => 0, 'bio' => 0,
            'shield' => 0, 'armor' => 20, 'hp' => 125,
            'mround' => 0, 'cool' => 22,
            'attack_ter' => 12, 'attack_air' => 20, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 208,
            'race_id' => 2,
            'init' => 7,
            'type' => 0, 'bio' => 0,
            'shield' => 0, 'armor' => 10, 'hp' => 150,
            'mround' => 0, 'cool' => 37,
            'attack_ter' => 30, 'attack_air' => 0, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 209,
            'race_id' => 2,
            'init' => 12,
            'type' => 0, 'bio' => 0,
            'shield' => 0, 'armor' => 20, 'hp' => 150,
            'mround' => 0, 'cool' => 75,
            'attack_ter' => 70, 'attack_air' => 0, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 210,
            'race_id' => 2,
            'init' => 0,
            'type' => 1, 'bio' => 0,
            'shield' => 0, 'armor' => 20, 'hp' => 150,
            'mround' => 0, 'cool' => 24,
            'attack_ter' => 0, 'attack_air' => 0, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 211,
            'race_id' => 2,
            'init' => 0,
            'type' => 1, 'bio' => 0,
            'shield' => 0, 'armor' => 20, 'hp' => 200,
            'mround' => 1, 'cool' => 24,
            'attack_ter' => 0, 'attack_air' => 0, 'attack_magic' => 1,
            'magic1' => 'Matrix', 'magic2' => 'EMI', 'magic3' => 'Radiation'
        ],
        [
            'id' => 212,
            'race_id' => 2,
            'init' => 6,
            'type' => 1, 'bio' => 0,
            'shield' => 0, 'armor' => 30, 'hp' => 200,
            'mround' => 0, 'cool' => 64,
            'attack_ter' => 0, 'attack_air' => 5, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 213,
            'race_id' => 2,
            'init' => 5,
            'type' => 1, 'bio' => 0,
            'shield' => 0, 'armor' => 10, 'hp' => 120,
            'mround' => 0, 'cool' => 22,
            'attack_ter' => 8, 'attack_air' => 20, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 214,
            'race_id' => 2,
            'init' => 6,
            'type' => 1, 'bio' => 0,
            'shield' => 0, 'armor' => 40, 'hp' => 500,
            'mround' => 1, 'cool' => 30,
            'attack_ter' => 25, 'attack_air' => 25, 'attack_magic' => 1,
            'magic1' => 'Yamato', 'magic2' => null, 'magic3' => null
        ]
    ],
    3 => [
        [
            'id' => 301,
            'race_id' => 3,
            'init' => 1,
            'type' => 0, 'bio' => 1,
            'shield' => 0, 'armor' => 10, 'hp' => 30,
            'mround' => 0, 'cool' => 15,
            'attack_ter' => 4, 'attack_air' => 0, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 302,
            'race_id' => 3,
            'init' => 1,
            'type' => 0, 'bio' => 1,
            'shield' => 0, 'armor' => 10, 'hp' => 40,
            'mround' => 0, 'cool' => 22,
            'attack_ter' => 5, 'attack_air' => 0, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 303,
            'race_id' => 3,
            'init' => 1,
            'type' => 0, 'bio' => 1,
            'shield' => 0, 'armor' => 10, 'hp' => 35,
            'mround' => 0, 'cool' => 8,
            'attack_ter' => 5, 'attack_air' => 0, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 304,
            'race_id' => 3,
            'init' => 4,
            'type' => 0, 'bio' => 1,
            'shield' => 0, 'armor' => 10, 'hp' => 80,
            'mround' => 0, 'cool' => 15,
            'attack_ter' => 10, 'attack_air' => 10, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 305,
            'race_id' => 3,
            'init' => 6,
            'type' => 0, 'bio' => 1,
            'shield' => 0, 'armor' => 20, 'hp' => 125,
            'mround' => 0, 'cool' => 37,
            'attack_ter' => 20, 'attack_air' => 0, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 306,
            'race_id' => 3,
            'init' => 1,
            'type' => 0, 'bio' => 1,
            'shield' => 0, 'armor' => 20, 'hp' => 400,
            'mround' => 0, 'cool' => 15,
            'attack_ter' => 20, 'attack_air' => 0, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 307,
            'race_id' => 3,
            'init' => 1,
            'type' => 0, 'bio' => 1,
            'shield' => 0, 'armor' => 10, 'hp' => 60,
            'mround' => 0, 'cool' => 24,
            'attack_ter' => 500, 'attack_air' => 0, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 308,
            'race_id' => 3,
            'init' => 0,
            'type' => 0, 'bio' => 1,
            'shield' => 0, 'armor' => 20, 'hp' => 80,
            'mround' => 1, 'cool' => 24,
            'attack_ter' => 0, 'attack_air' => 0, 'attack_magic' => 0,
            'magic1' => 'FogZ', 'magic2' => 'Plague', 'magic3' => null
        ],
        [
            'id' => 309,
            'race_id' => 3,
            'init' => 0,
            'type' => 1, 'bio' => 1,
            'shield' => 0, 'armor' => 10, 'hp' => 200,
            'mround' => 0, 'cool' => 24,
            'attack_ter' => 0, 'attack_air' => 0, 'attack_magic' => 10,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 310,
            'race_id' => 3,
            'init' => 3,
            'type' => 1, 'bio' => 1,
            'shield' => 0, 'armor' => 10, 'hp' => 120,
            'mround' => 0, 'cool' => 30,
            'attack_ter' => 9, 'attack_air' => 9, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 311,
            'race_id' => 3,
            'init' => 0,
            'type' => 1, 'bio' => 1,
            'shield' => 0, 'armor' => 10, 'hp' => 120,
            'mround' => 1, 'cool' => 24,
            'attack_ter' => 0, 'attack_air' => 0, 'attack_magic' => 1,
            'magic1' => 'Marker', 'magic2' => 'Guest', 'magic3' => 'Web'
        ],
        [
            'id' => 312,
            'race_id' => 3,
            'init' => 8,
            'type' => 1, 'bio' => 1,
            'shield' => 0, 'armor' => 30, 'hp' => 150,
            'mround' => 0, 'cool' => 30,
            'attack_ter' => 20, 'attack_air' => 0, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 313,
            'race_id' => 3,
            'init' => 6,
            'type' => 1, 'bio' => 1,
            'shield' => 0, 'armor' => 30, 'hp' => 250,
            'mround' => 0, 'cool' => 100,
            'attack_ter' => 0, 'attack_air' => 25, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ],
        [
            'id' => 314,
            'race_id' => 3,
            'init' => 1,
            'type' => 1, 'bio' => 1,
            'shield' => 0, 'armor' => 10, 'hp' => 25,
            'mround' => 0, 'cool' => 24,
            'attack_ter' => 0, 'attack_air' => 110, 'attack_magic' => 0,
            'magic1' => null, 'magic2' => null, 'magic3' => null
        ]
    ]
);