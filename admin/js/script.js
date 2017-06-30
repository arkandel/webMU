// Add Record
function addRecord() {
    // get values
    var name = $("#name").val();
    var description = $("#description").val();

    // Add record
    $.post("ajax/addRecord.php", {
        name: name,
        description: description,
    }, function (data, status) {
        // close the popup
        $("#add_new_record_modal").modal("hide");

        // read records again
        readRooms();

        // clear fields from the popup
        $("#name").val("");
        $("#description").val("");
    });
}

// READ records
function readRooms() {
    $.get("ajax/readRooms.php", {}, function (data, status) {
        $(".records_content").html(data);
    });
}


function DeleteRoom(id,name) {
    var conf = confirm("Are you sure, do you really want to delete " + name +" ?");
    if (conf == true) {
        $.post("ajax/deleteRoom.php", {
                id: id
            },
            function (data, status) {
                // reload Users by using readRooms();
                readRooms();
            }
        );
    }
}

function GetRoomDetails(id) {
    // Add Room ID to the hidden field for furture usage
    $("#hidden_room_id").val(id);
    $.post("ajax/readRoomDetails.php", {
            id: id
        },
        function (data, status) {
            // PARSE json data
            var room = JSON.parse(data);
            // Assing existing values to the modal popup fields
            $("#update_name").val(room.name);
            //$("#update_description").val(room.description);
            tinyMCE.activeEditor.setContent(room.description);
        }
    );
    // Open modal popup
    $("#update_room_modal").modal("show");
}

function UpdateRoomDetails() {
    // get values
    var name = $("#update_name").val();
    //var description = $("#update_description").val();
    description = tinyMCE.activeEditor.getContent();

    // get hidden field value
    var id = $("#hidden_room_id").val();

    // Update the details by requesting to the server using ajax
    $.post("ajax/updateRoomDetails.php", {
            id: id,
            name: name,
            description: description,
        },
        function (data, status) {
            // hide modal popup
            $("#update_room_modal").modal("hide");
            // reload rooms by using readRooms();
            readRooms();
        }
    );
}

$(document).ready(function () {
    // READ recods on page load
    readRooms(); // calling function
});