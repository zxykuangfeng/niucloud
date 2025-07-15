<?php
use think\facade\Route;

Route::group('toutiao', function () {
    Route::any('server', 'toutiao.Server/receiveTicket');
});