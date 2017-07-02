
// Initial values.
var filter="";
var perPage=2;
var currentPage=1;

// Add Record
function addRecord() {
    // get values
    var name = $("#name").val();
    //var description = $("#description").val();
    description = tinyMCE.activeEditor.getContent();

    // Add record
    $.post("ajax/addRecord.php", {
        name: name,
        description: description,
    }, function (data, status) {
        // close the popup
        $("#add_new_record_modal").modal("hide");

        // read records again
        readRooms("");

        // clear fields from the popup
        $("#name").val("");
        $("#description").val("");
    });
}

function addPagination(totalPages)
{
		var html = "<div id='content'></div><div id='pagination'><ul class='pagination'";
		//Pagination Numbers
		for(counter=1; counter<=totalPages; counter++)
		{
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
                $(".records_content").html(response.html + addPagination(response.totalPages));
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
                readRooms("");
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
            readRooms("");
        }
    );
}

    // The search/filter box   
    $("#searchbox").keyup( function() {
        var searchQuery = this.value;
        readRooms(searchQuery);
    });



    //Pagination - click grabbing, etc.
    $("#pagination li:first").css({'color' : '#FF0084'}).css({'border' : 'none'});

    //Pagination Click
    $("#pagination li").click(function()
    {
        //CSS Styles
        $("#pagination li").css({'border' : 'solid #dddddd 1px'}).css({'color' : '#0063DC'});
        $(this).css({'color' : '#FF0084'}).css({'border' : 'none'});
        //Loading Data
        console.log("Got clicked!");
        var currentPage = this.id;
        readRooms("", currentPage,2);
    });

$(document).ready(function () {
    readRooms(filter,currentPage,perPage); // starting things out.
});