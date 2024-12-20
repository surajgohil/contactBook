
        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title ml-auto" id="exampleModalLongTitle">Add Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addContactForm" isEdit="0">
                    <div class="modal-body" style="display: flex; flex-direction: column;">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="firstName" required>

                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="lastName" required>

                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>

                        <label for="number">Phone Number</label>
                        <input type="tel" class="form-control" id="number" name="number" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

        <footer class="main-footer">
            <p>Version 1.0</p>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.0.4/dist/js/adminlte.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {

            $("#sidebar").hover(function () {
                // On hover
                $(this).removeClass("sidebar-collapsed");
                $(this).addClass("sidebar-expanded");
            }, function () {
                // On hover out
                $(this).removeClass("sidebar-expanded");
                $(this).addClass("sidebar-collapsed");
            });

            let table = new DataTable('#contactNumberListing', {
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "<?php echo base_url('Dashboard/contactListing'); ?>",
                    "type": "POST",
                    "data": function (d) {
                        d.start = d.start;
                        d.length = d.length;
                        d.draw = d.draw;
                        d.search_value = d.search.value;

                        if (d.order && d.order.length > 0) {
                            const orderColumnIndex = d.order[0].column;
                            d.order_column = d.columns[orderColumnIndex].data;
                            d.order_dir = d.order[0].dir;
                        } else {
                            d.order_column = 'id';
                            d.order_dir = 'desc';
                        }
                    },
                    "dataSrc": function (json) {
                        if (json.data.length === 0) {
                            $('#contactNumberListing').find('tbody').html('<tr><td colspan="6" class="text-center" style="height:340px;">No data available in table.</td></tr>');
                        } else {
                            return json.data;
                        }
                        $('#contactNumberListing_processing').attr('style','display: none');
                    }
                },
                "columns": [
                    { "data": "id" },
                    { "data": "first_name" },
                    { "data": "last_name" },
                    { "data": "email" },
                    { "data": "number" },
                    { "data": "action" }
                ],
                "scrollX": true,
                "scrollY": '350px',
                "scroller": true,
                "order": [[0, 'desc']],
                "columnDefs": [
                    {
                        "targets": '_all',
                        "className": "text-center"
                    },
                    {
                        "targets": [5],
                        "orderable": false
                    }
                ]
            });

            $('#addContactForm').on('submit', function(e) {
                e.preventDefault();

                let form = new FormData(this);

                $('.displayError').remove();
                form.append('id', localStorage.getItem('editContactId'));

                $.ajax({
                    url  : '<?= base_url("Dashboard/saveChanges") ?>',
                    type : 'POST',
                    data : form,
                    contentType: false,
                    processData: false,
                    success : function(response){

                        localStorage.removeItem('editContactId');
                        
                        response = JSON.parse(response);

                        if(response.status === 3){
                            $.each(response.data, function(key, value) {
                                $(`[name="${key}"]`).after(`<span class="displayError text-danger">${value}</span>`);
                            });
                        }

                        if(response.status === 1){
                            $('#addNew').modal('hide');
                            table.ajax.reload();
                            $('#addContactForm')[0].reset();
                        }
                    }
                });
            });

            $(document).on('click', '.deleteContact', function(){

                if(confirm('Confirm to delete this contact.')){

                    let userId = $(this).attr('userid');
    
                    if(userId > 0){
    
                        $.ajax({
                            url  : '<?= base_url("Dashboard/deleteContact") ?>',
                            type : 'POST',
                            data : {userId : userId},
                            success : function(response){
                                response = JSON.parse(response);
                                if(response.status === 1){
                                    table.ajax.reload();
                                }else{
                                    alert('Something went wrong.');
                                }
                            }
                        });
                    }
                }
            });

            $(document).on('click', '.editContact', function(){

                let userId = $(this).attr('userid');

                localStorage.setItem('editContactId', userId);

                $('#addNew').modal('show');

                if(userId > 0){

                    $.ajax({
                        url  : '<?= base_url("Dashboard/getContactToEdit") ?>',
                        type : 'POST',
                        data : {userId : userId},
                        success : function(response){

                            response = JSON.parse(response);

                            if(response.status === 1){
                                $.each(response.data, function(key, value){
                                    key = (key == 'first_name') ? 'firstName' : key;
                                    key = (key == 'last_name') ? 'lastName' : key;
                                    $('[name="'+key+'"]').val(value);
                                });

                                $('#addContactForm').attr('isEdit', 1);

                            }else{
                                alert('Data is not found.');
                            }
                        }
                    });
                }
            });

            $('#logOut').on('click', function(){
                $.ajax({
                    url  : '<?= base_url("UserAction/redirectToLogOut") ?>',
                    type : 'POST',
                    success : function(response){
                        let data = JSON.parse(response);
                        if(data.status == 1){
                            window.location.href = "<?= base_url('signIn'); ?>";
                        }
                    }
                });
            });

            $('.deleteMultipleContacts').on('click', function(){
                if(confirm('Confirm to delete selected all contacts.')){
                    $('#selectContact').submit();
                }
            });

            $('#selectContact').on('submit', function(e){
                e.preventDefault();

                let form = new FormData(this);
                $.ajax({
                    url  : '<?= base_url("Dashboard/deleteMultipleContacts") ?>',
                    type : 'POST',
                    data : form,
                    contentType: false,
                    processData : false,
                    success : function(response) {
                        response = JSON.parse(response);
                        if(response.status === 1){
                            table.ajax.reload();
                            $('#selectContact')[0].reset();
                        }
                    }
                });
            });
            
            // if('.checkBox').on('click', function(){
            // });
        });
    </script>
</body>
</html>