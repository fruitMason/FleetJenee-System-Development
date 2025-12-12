@extends('layouts.master')

@section('page_title', 'Auto Part Purchase Order')



@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="col-md-12">
                @include('includes.error')
            </div>

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Auto Part Purchase Requests</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"> <a href="{{ route('finance.requests.home') }}">Requests</a> </li>
                            <li class="breadcrumb-item active">Create General Request</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="{{ route('inventory.purchase.index') }}" class="btn add-btn"><i class="fa fa-arrow-left"></i>
                            Back
                            to List</a>
                    </div>
                </div>
            </div>




            <section class="panel panel-default">
                <div class="card mg-b-20" id="card_content">
                    <div class="card-body">
                        <div class="card-title">Select Auto Part </div>
                        <form id="orderForm">
                            @csrf

                            <div class="row">
                                <!-- Input 1 -->
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">

                                        {{-- <div class="form-focus select-focus"> --}}
                                        <label for=" " class="">
                                            Auto Part <span class="text-danger">*</span>
                                        </label>

                                        <select id="auto_part_id" name="auto_part_id"
                                            class="select user-search2 form-control">
                                            <option value="">Select an auto part</option>
                                            @foreach ($autoParts as $part)
                                                <option value="{{ $part->id }}" data-price="{{ $part->unit_cost }}">
                                                    {{ $part->name }}</option>
                                            @endforeach
                                        </select>
                                        {{-- </div> --}}

                                    </div>
                                </div>


                                <!-- Input 2 -->
                                <div class="col-sm-6 col-md-2">
                                    <div class="form-group">
                                        <label for=" ">
                                            Unit Price <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" id="unit_price" name="unit_price" step="0.01"
                                            class="form-control">
                                    </div>
                                </div>

                                <!-- Input 3 -->
                                <div class="col-md-2">
                                    <div class="form-group">

                                        <label for=" ">
                                            Quantity<span class="text-danger">*</span>
                                        </label>
                                        <input type="number" id="quantity" name="quantity" min="1" value="1"
                                            class="form-control">
                                    </div>
                                </div>


                                <div class="col-sm-6  col-md-2">
                                    <div class="form-group">
                                        <label for="descriptiont" class="d-block mb-2">
                                            &nbsp;
                                        </label>
                                        <button type="button" id="addItem" class="btn btn-primary">
                                            Add to List
                                        </button>


                                    </div>
                                </div>



                            </div>
                        </form>

                    </div>
                </div>

                <div class="card mg-b-20" id="card_content">
                    <div class="card-body">
                        <div class="card-title">Request List </div>

                        <div class="bg-white rounded-lg p-6 mb-6">


                            <form id="submitOrder" method="POST" action="{{ route('inventory.purchase.store') }}"
                                onsubmit="return SubmitDelete(this,'Submit Auto Part Stock In Request');">
                                @csrf
                                <div class="overflow-x-auto">
                                    <div class="row">
                                        <div class="col-sm-6  col-md-4">
                                            <div class="form-group">
                                                <label>Purchase Date <span class="text-danger">*</span></label>
                                                <div class="cal-icon">
                                                    <input id="purchase_date" name="purchase_date"
                                                        class="form-control datetimepicker" type="text" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-2">
                                            <div class="form-group">
                                                <label>Reference <span class="text-danger">*</span></label>
                                                <input type="text" id="reference" name="reference" class="form-control"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>Message on Purchase (Remarks)</label>
                                                <textarea id="message" name="message" class="form-control" rows="2">Purchase Order</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <input id="totalValue" name="totalValue" type="hidden" required>

                                    <table class="table table-bordered table-responsive">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">Auto Part</th>
                                                <th scope="col">Unit Price</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemsList">
                                            <!-- Items will be added here dynamically -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-right text-xl">Grand Total:</td>
                                                <td id="grandTotal" class="text-xl">¢0.00</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button type="submit" id="submitBtn" disabled class="btn btn-success">
                                        Submit Order
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- //end start --}}

                    </div>
                </div>

            </section>




        </div>
    </div>
@endsection


@section('js')


    <script src="{{ asset('assets/js/select2.min.js') }}"></script>


    <script>
        $(document).ready(function() {
            console.log('document ready');

             $('.user-search2').select2();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const autoPartSelect = document.getElementById('auto_part_id');
            const unitPriceInput = document.getElementById('unit_price');
            const quantityInput = document.getElementById('quantity');
            const addItemBtn = document.getElementById('addItem');
            const itemsList = document.getElementById('itemsList');
            const grandTotalElement = document.getElementById('grandTotal');
            const submitBtn = document.getElementById('submitBtn');
            const submitOrderForm = document.getElementById('submitOrder');

            let items = [];
            let grandTotal = 0;

            // Update unit price when auto part is selected
            // autoPartSelect.addEventListener('change', function() {
            //     console.log('hit');
            //     const selectedOption = this.options[this.selectedIndex];
            //     unitPriceInput.value = selectedOption.dataset.price || '';
            // });

            // Use Select2's event:
            $('#auto_part_id').on('select2:select', function(e) {
                const selectedOption = e.params.data.element;
                const price = selectedOption.dataset.price || '';
                //document.getElementById('unit_price').value = price;
                unitPriceInput.value = price;
            });

            // Add item to the list
            addItemBtn.addEventListener('click', function() {
                const selectedOption = autoPartSelect.options[autoPartSelect.selectedIndex];
                const autoPartId = autoPartSelect.value;
                const autoPartName = selectedOption.text;
                const unitPrice = parseFloat(unitPriceInput.value);
                const quantity = parseInt(quantityInput.value);

                if (!autoPartId || isNaN(unitPrice) || isNaN(quantity) || quantity < 1) {
                    alert('Please select an auto part and enter a valid quantity');
                    return;
                }

                const total = unitPrice * quantity;

                // Add to items array
                items.push({
                    auto_part_id: autoPartId,
                    name: autoPartName,
                    unit_price: unitPrice,
                    quantity: quantity,
                    total: total
                });

                // Update the list
                updateItemsList();

                // Reset form
                autoPartSelect.value = '';
                unitPriceInput.value = '';
                quantityInput.value = '1';
            });

            // Update the items list in the DOM
            function updateItemsList() {
                // Clear the list
                itemsList.innerHTML = '';
                grandTotal = 0;

                // Add each item to the list
                items.forEach((item, index) => {
                    grandTotal += item.total;

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="">
                            ${item.name}
                            <input type="hidden" name="items[${index}][auto_part_id]" value="${item.auto_part_id}">
                            <input type="hidden" name="items[${index}][name]" value="${item.name}">
                        </td>
                        <td class="">
                            ¢${item.unit_price.toFixed(2)}
                            <input type="hidden" name="items[${index}][unit_price]" value="${item.unit_price}">
                        </td>
                        <td class="">
                            ${item.quantity}
                            <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                        </td>
                        <td class="">
                            ¢${item.total.toFixed(2)}
                            <input type="hidden" name="items[${index}][total]" value="${item.total}">
                        </td>
                        <td class="">
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${index})">Remove</button>
                        </td>
                    `;
                    itemsList.appendChild(row);
                });

                // Update grand total
                grandTotalElement.textContent = `¢${grandTotal.toFixed(2)}`;
                document.getElementById("totalValue").value = grandTotal;


                // Enable/disable submit button
                submitBtn.disabled = items.length === 0;
            }

            // Remove item from the list
            window.removeItem = function(index) {
                items.splice(index, 1);
                updateItemsList();
            };
        });
    </script>


@endsection
