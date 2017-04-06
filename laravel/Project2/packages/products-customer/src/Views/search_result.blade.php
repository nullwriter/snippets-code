
@if(!empty($product))
    <div class="row" id="product-detail">
        <ul style="list-style: none;">
            <li><img style="float: left;margin-right: 1rem;" src="http://placehold.it/150x150"></li>
            <li><b>{{$product->description}}</b></li>
            <li><b>Code:</b> {{$product->internal_id}} / <b>Model:</b> {{$product->model}}</li>
            <li><b>Category:</b> {{$product->category->parent->parent->description}}</li>
            <li><b>Line:</b> {{$product->category->parent->description}}</li>
            <li><b>Sub-Line:</b> {{$product->category->description}}</li>
            <li><b>Brand:</b> {{$product->brand->description}}</li>
        </ul>
    </div>

    <div style="margin-top: 1.5rem;" id="product-sales-organizations">
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
@else
<div style="width: 100%;text-align: center; padding:6rem;">
    <span>No se encontro ningun producto</span>
</div>
@endif


<!--

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

                -->