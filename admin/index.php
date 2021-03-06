<!DOCTYPE html> 
<html lang="en"> 
<head>
    <meta charset="UTF-8"> 
    <title>World Builder Tool</title>

    <!-- Bootstrap CSS File  -->
    <link rel="stylesheet" type="text/css" href="../js/bootstrap-3.3.5-dist/css/bootstrap.css"/>
    <script type="text/javascript" src="../js/tinymce/tinymce.min.js"></script>  
	
</head>
<body>

<!-- Content Section -->

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>WebMU World Editor</h1>
        </div>
    </div>
    <div class="row">
    Filter: <input id="searchbox" type="text" placeholder="Start typing..."/>
 
        <div class="col-md-12">
            <div class="pull-right">
                <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New Record</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3>Records:</h3>

            <div class="records_content"></div>
        </div>
    </div>
</div>
<!-- /Content Section -->


<!-- Bootstrap Modals -->
<!-- Modal - Add New Record/Room -->
<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add New Room</h4>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label for="name">Room Name</label>
                    <input type="name" id="name" placeholder="Room Name" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="description">Room Description</label>
                    <textarea id="description" placeholder="Room Description" rows="20" cols="80" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label for="coordx">Coordinate X:</label>
                    <input type="coordx" id="coordx" placeholder="" maxlength="2" size="2"/>
                </div>
                <div class="form-group">
                    <label for="coordy">Coordinate Y:</label>
                    <input type="coordy" id="coordy" placeholder="" maxlength="2" size="2"/>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addRecord()">Add Record</button>
            </div>
        </div>
    </div>
</div>
<!-- // Modal -->

<!-- Modal - Update Room details -->
<div class="modal fade" id="update_room_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Update</h4>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label for="update_name">Room Name</label>
                    <input type="text" id="update_name" placeholder="Room Name" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="update_description">Room Description</label>
                    <textarea id="update_description" placeholder="Room Description" rows="20" cols="80" class="form-control"></textarea>
                    <script>
                        tinymce.init({ selector: "#update_description" });
                    </script>
                </div>

               <div class="form-group">
                    <label for="update_coordx">Coordinate X:</label>
                    <input type="update_coordx" id="update_coordx" placeholder="" maxlength="2" size="2"/>
                </div>
                <div class="form-group">
                    <label for="update_coordy">Coordinate Y:</label>
                    <input type="update_coordy" id="update_coordy" placeholder="" maxlength="2" size="2"/>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="UpdateRoomDetails()" >Save Changes</button>
                <input type="hidden" id="hidden_room_id">
            </div>
        </div>
    </div>
</div>
<!-- // Modal -->

<!-- Jquery JS file -->
<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>

<!-- Bootstrap JS file -->
<script type="text/javascript" src="../js/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

<!-- Custom JS file -->
<script type="text/javascript" src="js/script.js"></script>
</body>
</html>
