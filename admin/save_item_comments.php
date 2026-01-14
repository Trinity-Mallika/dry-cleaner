<?php
include_once("../adminsession.php");

$item_id = $_POST['item_id'] ?? 0;
$comment_ids = $_POST['comment_ids'] ?? [];

if ($item_id == 0 || empty($comment_ids)) {
    exit;
}

foreach ($comment_ids as $cid) {
    $exists = $obj->getvalfield(
        "item_comment_map",
        "map_id",
        "item_id='$item_id' AND comment_id='$cid'"
    );

    if ($exists == 0) {
        $obj->insert_record("item_comment_map", [
            "item_id"    => $item_id,
            "comment_id" => $cid,
            "createdby"  => $loginid
        ]);
    }
}

echo "success";
