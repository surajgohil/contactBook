<style>
    .dt-length > label,
    .dt-search > label{
        font-size: var(--mdc-typography-subtitle2-font-size, 0.875rem) !important;
        font-family: var(--mdc-typography-subtitle2-font-family, var(--mdc-typography-font-family, Roboto, sans-serif)) !important;
        font-weight: 100 !important;
    }
    .dt-length > label{
        margin-left: 5px;
    }
    .dt-search > #dt-search-0{
        background-color: #ffffff !important;
        border: 1px solid #767676 !important;
    }
    .mdc-button--raised{
        background-color: #0d6efd !important;
        color:rgb(255, 255, 255) !important;
    }
    /* .mdc-button {
        color: #0d6efd;
    } */

    /* .mdc-button:not(:disabled) {
        color: #0d6efd !important;
        color: var(--mdc-text-button-label-text-color, var(--mdc-theme-primary, #0d6efd)) !important;
    } */
</style>
<div class="content-wrapper">
    <!-- <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
            </div>
        </div>
    </div> -->

    <section class="content">   
        <table id="contactNumberListing" class="display mdl-data-table" style="width:100%">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </section>
</div>