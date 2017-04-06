<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="width: 100%;">
    <div class="modal-dialog" style="width: 72rem;">
        <div class="modal-content">
            <div class="modal-header" style="text-align: left;background-color: #e0e0e0;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">@lang('pcustomer::text.general.products') (#{!! $idCustomer !!})</h4>
            </div>
            <div class="modal-body">

                <form class="form-inline row">
                    <div class="form-group" style="float: right;margin-right: 1.5rem;">
                        <!--label for="search-cust-product">Product Code</label-->
                        <input value="G2033.235" style="width: 30rem;" id="search-cust-product" class="form-control col-sm-12" type="text" value=""  />
                        <a class="btn btn-flat btn-default search-prod-btn">
                            @lang('pcustomer::text.general.search')
                        </a>
                    </div>
                </form>
                <br >

                <!-- LISTA -->
                <div class="row">
                    <ul style="list-style: none;">
                        <li><img style="float: left;margin-right: 1rem;" src="http://placehold.it/150x150"></li>
                        <li><b>RELAY 101-9073 GE 1/3HP WH12X235</b></li>
                        <li><b>Code:</b> G2033.235 / <b>Model:</b> WH12X235</li>
                        <li><b>Category:</b> APPLIANCES PARTS</li>
                        <li><b>Line:</b> APPLIANCES PARTS</li>
                        <li><b>Sub-Line:</b> WASHING MACHINE PARTS</li>
                        <li><b>Brand:</b> NO APLICA</li>
                    </ul>
                </div>

                <!-- TABLA -->
                <div class="row" style="margin-top: 1.5rem;">
                    <table class="table table-hover table-striped table-border"
                           style="width: 90%;margin: 0 auto;text-align: center;">
                        <thead>
                        <tr class="table-border" style="background-color: #eaeaea">
                            <td class="table-border" style="font-weight: bold;">@lang('pcustomer::text.general.company')</td>
                            <td class="table-border hidden-sm" style="font-weight: bold;">@lang('pcustomer::text.general.organization')</td>
                            <td class="table-border" style="font-weight: bold;">@lang('pcustomer::text.general.price_type')</td>
                            <td class="table-border" style="font-weight: bold;">@lang('pcustomer::text.general.block')</td>
                            <td class="table-border" style="font-weight: bold;">@lang('pcustomer::text.general.price')</td>
                            <td class="table-border" style="font-weight: bold;">@lang('pcustomer::text.general.surcharge')</td>
                            <td class="table-border" style="font-weight: bold;">@lang('pcustomer::text.general.available_sale')</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="table-border">
                            <td class="table-border">USA Export</td>
                            <td class="table-border hidden-sm">M2131</td>
                            <td class="table-border">Y5</td>
                            <td class="table-border">N</td>
                            <td class="table-border">X</td>
                            <td class="table-border">X</td>
                            <td class="table-border">No</td>
                        </tr>
                        <tr class="table-border">
                            <td class="table-border">Panama</td>
                            <td class="table-border hidden-sm">C3131</td>
                            <td class="table-border">Y5</td>
                            <td class="table-border">N</td>
                            <td class="table-border">X</td>
                            <td class="table-border">X</td>
                            <td class="table-border">Yes</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat btn-default" data-backdrop="false" data-dismiss="modal">@lang('pcustomer::text.general.close')</button>
            </div>
        </div>
    </div>
</div>