<?php
use think\facade\Route;

Route::group('toutiao', function () {
    Route::post('server', 'toutiao.Server/receiveTicket');
});