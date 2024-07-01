<?php
include 'config/winningLines.php';
include 'config/winningSymbols.php';

header('Content-type: application/json');

// Start the session
session_start();
ob_start();
/* @TODO Calculate result from database data */



/* Simulate result */
$gameData = [
    "win"=> false, 
    "winAmount"=> 0,
];

$currentGame = NULL;
$betAmount = 0;

if(isset($_SESSION["gameData"])) {
    $currentGame = $_SESSION["gameData"];
    $betAmount = floatval($currentGame["betAmount"]);

    $hasWin = false;
    $winAmount = 0;

    foreach($currentGame["spinResult"]["winSymbols"] as $reelWinSymbols) {
        if(count($reelWinSymbols["reelWinSymbols"]) == 0) continue;
        $hasWin = true;

        $firstSymbol = $reelWinSymbols["reelWinSymbols"][0];
        if(intval($firstSymbol["x"]) != 0) continue;
        
        $symbol = $firstSymbol["symbol"];

        $nbConnection = $firstSymbol["nbConnection"];
        $winAmount += $winBySymbol[$symbol - 1][$nbConnection - 1];
    }
    
    $gameData["winAmount"] = ($winAmount * $betAmount);
    $gameData["win"] = $hasWin;    
}
else {
    $gameData["win"] = false;
    $gameData["winAmount"] = 0;
}

$_SESSION["balance"] += $gameData["winAmount"];
$resp = $gameData;

echo json_encode($resp);

?>