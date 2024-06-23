<?php 
//generating an id for rows
//four pars will be passed: conn,the sql statement,the preffix of row id, and the column name
function RemovePreffix($index,$preffix) {
    $res0 = str_replace( array( "$preffix"."00","$preffix","$preffix"."0"), '', $index);
    return $res0;
}
function addPreffix($preffix,$index1) {
    if($index1<10){
        $res1 = "$preffix"."00".$index1;
        return $res1;
    }
    elseif($index1>=10){
        $res1 = "$preffix"."0".$index1;
        return $res1;
    }
}
function genId($conn,$sqlStatement,$idPreffix,$colName){
    $resultAdm = $conn->query($sqlStatement);
    $idPreffix=$idPreffix;
    $dm_adms = array(0);
    while ($admrowdb = mysqli_fetch_assoc($resultAdm)) {
        $newRow = $admrowdb["$colName"];
        $index = RemovePreffix($newRow,$idPreffix);
        $newRow = $index;
        array_push($dm_adms, $newRow);
    }
    $cur_max = max($dm_adms);
    if($cur_max=="$idPreffix"."001"){
        $newId = "$idPreffix"."002";
    }
    else{
        $newId = $cur_max+=1;
        $index1 = addPreffix($idPreffix,$newId);
        $newId = $index1;
    }
    return $newId;
}