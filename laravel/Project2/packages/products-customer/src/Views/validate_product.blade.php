<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="width: 100%;">
    <div class="modal-dialog" style="width: 72rem;">
        <div class="modal-content">
            <div class="modal-header" style="text-align: left;background-color: #e0e0e0;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">@lang('pcustomer::text.general.products') ({!! $id !!})</h4>
            </div>
            <div class="modal-body">

                <form class="form-inline row">
                    <div class="form-group" style="float: right;margin-right: 1.5rem;">
                        <input placeholder="Product Code" onclick="AutoComplete.search('internal_id','products')" style="width: 30rem;" id="search-input-modal" class="form-control col-sm-12" type="text" value=""  />
                        <a class="btn btn-flat btn-default search-prod-btn" id="search-prod" onclick="ProductCustomer.productSearch()">
                            @lang('pcustomer::text.general.search')
                        </a>
                    </div>
                </form>
                <br >

                <!-- LISTA -->
                <div id="product-detail">
                </div>

                <!-- TABLA -->
                <div class="row" style="margin-top: 1.5rem;" id="product-sales-organizations">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat btn-default" data-backdrop="false" data-dismiss="modal">@lang('pcustomer::text.general.close')</button>
            </div>
        </div>
    </div>
</div>
@include('backend.includes.partials.autocomplete')