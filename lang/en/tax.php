<?php


return [
  "attributes" => [
      "name" => "Name",
      "rate" => "Rate",
      "is_active"=>"Active",
      "is_default"=>"Default",
  ],
    "messages" => [
        "saved" => "Tax saved successfully",
        "deleted" => "Tax deleted successfully",
        "updated" => "Tax updated successfully",
        "not_found" => "Tax not found",
        "deleted_error" => "Tax could not be deleted",
        "updated_error" => "Tax could not be updated",
        "saved_error" => "Tax could not be saved",
        'deleted_default_error' => 'Cannot delete the default tax. Please set another tax as default first.',
    ]
];
