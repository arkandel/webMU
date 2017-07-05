var perPage=5; // Globalize it now. Later on we might put it in an input box.

// Add Record
function addRecord() {
    // get values
    var name = $("#name").val();
    var description = $("#description").val();
    //description = tinyMCE.activeEditor.getContent();

    // Add record
    $.post("ajax/addRecord.php", {
        name: name,
        description: description,
    }, function (data, status) {
        // close the popup
        $("#add_new_record_modal").modal("hide");

        // read records again
		filter = searchbox.value;
		currentPage = $( "li[value='current']" ).html();
        readRooms(filter,currentPage,perPage);

        // clear fields from the popup
        $("#name").val("");
		$("#description").val("");
		tinyMCE.activeEditor.setContent("");
    });
}

function addPagination(totalPages, currentPage)
{
		var html = "<div id='content'><div id='pagination'>Pages: <ul class='pagination'>";
		//Pagination Numbers
		for(counter=1; counter<=totalPages; counter++)
		{
			if(counter==currentPage)
				html += "<li value='current' id=" + counter + ">" + counter + "</li>";
			else
				html += "<li id=" + counter + ">" + counter + "</li>";
		}
		html += "</ul></div></div>";
        return(html);
}

function readRooms(filter, currentPage, perPage)
{
        $.post("ajax/readRooms.php",
            {
                filter: filter,
                currentPage: currentPage,
                perPage: perPage
            },
            function (data, status)
            {
                var response = JSON.parse(data);

                // reload Users by using readRooms();
                $(".records_content").html(response.html + addPagination(response.totalPages, currentPage));
            }
        );
}

function DeleteRoom(id,name) {
    var conf = confirm("Are you sure, do you really want to delete " + name +" ?");
    if (conf == true) {
	$.post("ajax/deleteRoom.php", {
                id: id
            },
            function (data, status) {
                // reload rooms in the UI by calling up readRooms();
				filter = searchbox.value;
                readRooms(filter,currentPage,perPage);
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
    $.post("ajax/updateRoomDetails.php",
		{
            id: id,
            name: name,
            description: description,
        },
        function (data, status)
		{
            // hide modal popup
            $("#update_room_modal").modal("hide");
            // reload rooms by using readRooms();
            readRooms(filter, currentPage, perPage);
        }
    );
}

    // The search/filter box
    $("#searchbox").keyup( function() {
        var searchQuery = this.value;
        readRooms(searchQuery, currentPage, perPage);
    });

    //Pagination - click grabbing, etc.
    $(document).on('click', 'li', function()
    {
		filter = searchbox.value;
        currentPage = this.id;
        readRooms(filter, currentPage,perPage);
    });

$(document).ready(function () {
	var filter = "";
	var currentPage = 1;
    readRooms(filter,currentPage,perPage); // Starting things out.
});
