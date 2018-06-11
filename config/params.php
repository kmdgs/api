<?php
return [
    // 速度控制[120,60] 代表  60秒内最大访问120次，
    'rateLimit'             => [
        'enable'=> true,   # 是否开启？默认不开启速度控制。
        'limit' => [5, 60],
    ],
];
