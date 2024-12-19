
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
                <form id="addContactForm">
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

            $('#addContactForm').on('submit', function(e) {
                e.preventDefault();

                let form = new FormData(this);

                $('.displayError').remove();

                $.ajax({
                    url  : '<?= base_url("Dashboard/saveContact") ?>',
                    type : 'POST',
                    data : form,
                    contentType: false,
                    processData: false,
                    success : function(response){

                        response = JSON.parse(response);
                        if(response.status === 3){
                            $.each(response.data, function(key, value) {
                                console.log('key : ',key);
                                console.log('value : ',value);
                                $(`[name="${key}"]`).after(`<span class="displayError text-danger">${value}</span>`);
                            });
                        }

                        if(response.status === 1){
                            table.ajax.reload();
                            clsoseAllModal();
                        }
                    }
                });
            });

            let table = new DataTable('#contactNumberListing', {
                "processing": false,
                "serverSide": true,
                "ajax": {
                    "url": "<?php echo base_url('Dashboard/contactListing'); ?>",
                    "type": "POST",
                    "data": function (d) {
                        d.start = d.start;
                        d.length = d.length;
                        d.draw = d.draw;
                        d.search_value = d.search.value;
                        d.order_column = d.columns[d.order[0].column].data;
                        d.order_dir = d.order[0].dir;
                    },
                    "dataSrc": function (json) {
                        if (json.data.length === 0) {
                            $('#contactNumberListing').find('tbody').html('<tr><td colspan="5" class="text-center" style="height:340px;">No data available in table.</td></tr>');
                        } else {
                            return json.data;
                        }
                    }
                },
                "columns": [
                    { "data": "first_name" },
                    { "data": "last_name" },
                    { "data": "email" },
                    { "data": "number" },
                    { "data": "action" }
                ],
                "scrollX": true,
                "scrollY": '350px',
                "scroller": true,
            });


            $(document).on('click', '.deleteContact', function(){

                let userId = $(this).attr('userid');

                if(userId > 0){
                    table.ajax.reload();
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
        });


        function clsoseAllModal(id = ''){
            // if(id == ''){
            //     alert(id);
            //     $('.modal').modal('hide');
            // }else{
                $('#exampleModalLongTitle').click();
            // }
        }
    </script>
</body>
</html>