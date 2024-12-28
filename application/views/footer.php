       
        <!-- Modal -->
        <div class="modal fade" id="groupModal" tabindex="-1" role="dialog" aria-labelledby="groupModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title ml-auto" id="exampleModalLongTitle">Group Form</h5>
                        <button type="button" class="close gropModelCloseBtn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="groupForm">
                        <div class="modal-body">
                            <label>Group Name</label>
                            <input type="text" class="form-control" id="groupName" name="groupName" required>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        
        
        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title ml-auto" id="exampleModalLongTitle">Contact Form</h5>
                    <button type="button" class="close closeModalBtn" data-dismiss="modal" aria-label="Close">
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
                        <input type="email" class="form-control" id="email" name="email">

                        <label for="number">Phone Number</label>
                        <input type="tel" class="form-control" id="number" name="number" required>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary closeModalBtn" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

        <footer class="main-footer" style="background: #343a40;">
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

        localStorage.removeItem('groupId');

        // Render table with dataTable.
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

                    d.groupId = 0;
                    if (localStorage.hasOwnProperty('groupId')) {
                        d.groupId = localStorage.getItem('groupId');
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
                    "targets": [0,5],
                    "orderable": false
                }
            ],
            "responsive": true
        });


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

            // Add and Edit contact.
            $('#addContactForm').on('submit', function(e) {
                e.preventDefault();

                let form = new FormData(this);

                $('.displayError').remove();
                form.append('id', localStorage.getItem('editContactId'));

                let groupId = 0;
                if (localStorage.hasOwnProperty('groupId')) {
                    groupId = localStorage.getItem('groupId')
                }
                form.append('groupId', groupId);

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
                        }
                    }
                });
            });

            // Delete contact.
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

            // Get data for edit.
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

            // logout user.
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

            // Submit form of delete all selected contact.
            $('.deleteMultipleContacts').on('click', function(e){
                if(confirm('Confirm to delete selected all contacts.')){
                    $('#selectContact').submit();
                }
            });

            // Delete all selected contact.
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
                            $('.deleteMultipleContacts').prop('disabled', true);
                        }
                    }
                });
            });

            // Close model by open edit button.
            $('.closeModalBtn').on('click', function(){
               $('#addNew').modal('hide');
            });

            // Reset form.
            $('.addContactBtn').on('click', function(){
                $('#addContactForm')[0].reset();
            });

            // Group Form.
            $('#groupForm').on('submit', function(e){
                e.preventDefault();

                $('#selectContact')[0].reset();
                let form = new FormData(this);

                $.ajax({
                    url  : '<?= base_url("Dashboard/saveGroup") ?>',
                    type : 'POST',
                    data : form,
                    contentType: false,
                    processData : false,
                    success : function(response) {
                        response = JSON.parse(response);
                        if(response.status === 3){
                            $.each(response.data, function(key, value) {
                                $(`[name="${key}"]`).after(`<span class="displayError text-danger">${value}</span>`);
                            });
                        }
                        if(response.status === 1){
                            groupListing();
                            $('#groupForm')[0].reset();
                            $('#groupModal').modal('hide');
                            $('.modal-backdrop').remove();
                        }
                    }
                });
            });

            $(document).on('click', '.checkBox', function() {
                if ($('.checkBox:checked').length > 0) {
                    $('.deleteMultipleContacts').prop('disabled', false);
                } else {
                    $('.deleteMultipleContacts').prop('disabled', true);
                }
            });

            $(document).on('change', '.selectGroup', function(){

                let numberId = $(this).attr('numberid');
                let groupId = $(this).val();

                if(numberId !== undefined && groupId !== undefined){
                    $.ajax({
                        url  : '<?= base_url("Dashboard/contactMoveToGroup") ?>',
                        type : 'POST',
                        data : {
                            'numberId' : numberId,
                            'groupId' : groupId
                        },
                        success : function(response) {
                            response = JSON.parse(response);
                            if(response.status === 1){
                                table.ajax.reload();
                            }
                        }
                    });
                }
            });

            $(document).on('click', '#navbarMenu > li', function(){

                let groupId = $(this).attr('groupId');
                localStorage.setItem('groupId', groupId);
                table.ajax.reload();
                $('#navbarMenu > li > a').removeClass('bg-primary');
                $(this).find('a').addClass('bg-primary');
            });

            $(document).on({
                mouseenter: function() {
                    $(this).find('.options').removeClass('d-none');
                    $('.groupName_' + $(this).attr('groupId')).css('width','55%');
                },
                mouseleave: function() {
                    $(this).find('.options').addClass('d-none');
                    $('.groupName_' + $(this).attr('groupId')).css('width','90%');
                },
            }, '#navbarMenu > li');

            groupListing();
        });


        function groupListing(){

            $.ajax({
                url  : '<?= base_url("Dashboard/groupListing") ?>',
                type : 'POST',
                success : function(response) {

                    response = JSON.parse(response);

                    if(response.status === 1){
                        let html = '';
                        $.each(response.data, function(key, value){

                            html += `<li class="nav-item" groupId="${value.id}">
                                            <a href="#" class="nav-link" style="position: relative;">
                                                <i class="fa-solid fa-user-group"></i>
                                                <p class="addInput_${value.id}" style="text-transform: capitalize; text-overflow: ellipsis; width: 13ch; overflow: clip;" >${value.name}</p>
                                                <div class="d-none options" style="display: flex;position: absolute;top: 20%; right: 5%; z-index: 100;">
                                                    <div class="btn btn-warning" onclick="renameGroup(${value.id}); event.stopPropagation();" style="display: flex; justify-content: center; align-items: center; width: 25px; height: 25px;font-size: 12px;">
                                                        <i class="fas fa-edit"></i>
                                                    </div>
                                                    <div class="btn btn-danger ml-2" onclick="deleteGroup(${value.id}); event.stopPropagation();" style=" display: flex; justify-content: center; align-items: center; width: 25px; height: 25px;font-size: 12px;">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    `;
                        });
                        $('#navbarMenu').html(html);
                        $('.gropModelCloseBtn').click();
                    }
                }
            });
        }

        function renameGroup(groupId){

            if(groupId > 0){

                let name = $('.addInput_'+groupId).text();

                $('.addInput_'+groupId).removeAttr('style');
                $('.addInput_'+groupId).html(`
                    <input type="text" class="form-control groupName groupName_${groupId}" groupId="${groupId}" value="${name}" style="width: 55%; height: 30px; padding-left: 8px;">
                `);
                
            }
        }

        $(document).on('keyup', '.groupName', function(e){

            let groupId = $(this).attr('groupId');
            let groupName = $(this).val();

            if(groupName !== ''){

                clearTimeout($(this).data('timeout'));
    
                $(this).data('timeout', setTimeout(function() {

                    $.ajax({
                        url  : '<?= base_url("Dashboard/renameGroup") ?>',
                        type : 'POST',
                        data : {
                            'groupId' : groupId,
                            'groupName' : groupName
                        },
                        success : function(response) {
                        }
                    });

                    if (e.which === 13) {
                        $('.addInput_'+groupId).attr('style', 'text-transform: capitalize; text-overflow: ellipsis; width: 13ch; overflow: clip;');
                        $('.addInput_'+groupId).html(groupName);
                    }

                }, 500));
            }
        });

        function deleteGroup(groupId){

            if(groupId > 0){

                $.ajax({
                    url  : '<?= base_url("Dashboard/deleteGroup") ?>',
                    type : 'POST',
                    data : {
                        'groupId' : groupId,
                    },
                    success : function(response) {
                        localStorage.removeItem('groupId');
                        table.ajax.reload();
                        groupListing();
                    }
                });
            }
        }

        $(document).on('.modal', function(e) {
            $(this).modal('hide');
            alert('ahsdiuasd');
        });
    </script>
</body>
</html>