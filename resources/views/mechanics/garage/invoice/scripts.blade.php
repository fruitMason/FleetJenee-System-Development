<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {

        let item_type = 'car'

        let product_rows = [];

        let discount_type = ``

        let discount = 0

        let fetched_products = []

        function styleSelectTags()
        {
            $(".selected_grid_product").select2({
                tags: true
            });
            $(".selected_grid_tax").select2();
            $(".discount_interest").select2();
            $(".discount_interest_type").select2();
        }

        let fetched_taxes = []

        let discount_array = [
            {'name': 'Discount','value': 'discount'},
            {'name': 'Interest','value': 'interest'}
        ]

        let discount_types_array = [
            {'name': 'Percentage','value': 'percentage'},
            {'name': 'Fixed Amount','value': 'amount'}
        ]

        let input_description = {
            row_id : 0,
            description: ''
        }

        let input_price = {
            row_id: 0,
            unit_price: 0
        }

        let discount_interest_value = {
            row_id: 0,
            value: 0
        }

        let discount_interest = {
            row_id: 0,
            type: ''
        }

        let discount_interest_type = {
            row_id: 0,
            type: ''
        }

        let prepared_products = [];

        let fetch_products_url = `{{route('getProductsAndServices')}}`

        let fetch_taxes_url = `{{route('getTaxes')}}`

        fetchData(fetch_products_url)

        fetchData(fetch_taxes_url)

        function fetchData(url)
        {
            waitme("row_item_table");

            $.ajax({
                url
            }).done((data) => {
                if(!jQuery.isEmptyObject(data))
                {
                    if(url === `{{route('getTaxes')}}`)
                    {
                        fetched_taxes = data

                    }else {

                        let product_data = {}

                        fetched_products = data

                        $.each(fetched_products, function (i, product) {

                            product_data.id = product.id

                            product_data.description = product.model+' ('+product.car_number+')'

                            product_data.item_type = 'car'

                            product_data.quantity = 1

                            product_data.unit_price = product.car_cost !== null ? product.car_cost : 1

                            product_data.sub_total = product.car_cost !== null ? product.car_cost : 1

                            prepared_products.push(product_data)

                            product_data = {}
                        })
                    }
                    hidewaitme('row_item_table');
                }
            })
        }

        $('#add_prod_btn').on('click', function () {
            displayRowItems()
            addRow();
        })

        function updateItemQuantity(selected_row_id, input_item_quantity)
        {

            let row_id = selected_row_id.split(',')[0]

            let item_type = selected_row_id.split(',')[1]

            let current_row_content = product_rows.find(row_data => row_data.row_id === Number(row_id))

            current_row_content.row_sub_total = Number(current_row_content.unit_price) * Number(input_item_quantity)

            current_row_content.quantity = input_item_quantity

            //recalculate tax when there the quantity change
            populateRowTax(row_id, current_row_content.selected_tax_ids_from_grid)

            displayRowItems()

            $('.input_quantity').focus().val(input_item_quantity);

            if($('.input_quantity').is('.input_quantity:last') && $('.input_quantity').last()){
                // alert('wow')
                $('.input_quantity').focus().val(input_item_quantity);
            }
        }


        function populateRowTax(row_id, selected_tax_ids_from_grid)
        {
            //get the content of row from which the product was selected
            let current_row_content = product_rows.find(row_data => row_data.row_id === Number(row_id))

            //set the sub_total for the row content
            let sub_total = current_row_content.quantity * current_row_content.unit_price

            let vat_percentage = 0;

            let taxes = []

            let new_sub_total = 0

            //check if discount was applied
            new_sub_total = sub_total

            //loop tru the selected taxes ids
            $.each(selected_tax_ids_from_grid, function (i, selected_tax_id){

                console.log(selected_tax_id)
                //find the tax
                let selected_tax = fetched_taxes.find(tax_data => tax_data.id === Number(selected_tax_id))

                console.log('this is the selected tax: ', selected_tax)

                //check if its vat
                if(selected_tax !== undefined) {
                    if(selected_tax.name === 'VAT')
                    {
                        //set the tax percentage
                        vat_percentage = Number(selected_tax.percentage)

                    }
                    else{

                        let computed_tax_amount = (new_sub_total * selected_tax.percentage) / 100;

                        current_row_content[selected_tax.name] = computed_tax_amount

                        //push tax into array
                        taxes.push(computed_tax_amount)
                    }
                }

            })

            //for VAT tax
            if(vat_percentage > 0)
            {
                //sum all taxes
                let sum_of_taxes = taxes.reduce((a, b) => a + b, 0)

                let selected_tax = fetched_taxes.find(tax_data => tax_data.name === 'VAT')

                //find the vat percentage of taxes and the subtotal and push into tax array

                let computed_vat_amount = ((new_sub_total + sum_of_taxes) * vat_percentage) / 100

                current_row_content[selected_tax.name] = computed_vat_amount

                taxes.push(computed_vat_amount)
            }

            //reset row subtotal
            current_row_content.tax_amount = taxes.reduce((a, b) => a + b, 0)

            current_row_content.selected_tax_ids_from_grid = selected_tax_ids_from_grid

            displayRowItems()
            GetIndividualTaxes();
        }


        function GetIndividualTaxes(){

            // Init Variables

            let tax_percentage = {
                    nhil : 0,
                    vat: 0,
                    getfund: 0,
                    covid: 0,
                    cst: 0,
                    flat_vat: 0,
                },
                tax_values = {
                    nhil: 0,
                    vat: 0,
                    getfund: 0,
                    covid: 0,
                    cst: 0,
                    flax_vat: 0,
                },
                sub_total = 0,
                item_type = '',
                all_total = 0,
                tax_total = {
                    nhil: 0,
                    vat: 0,
                    getfund: 0,
                    covid: 0,
                    cst: 0,
                    flax_vat: 0,
                    vat_standard: 0,
                },
                _sub_total = 0,
                math = {
                    sub: 0,
                    vat: 0,
                    net: 0,
                    getfund: 0,
                    nhil: 0,
                    cst: 0,
                    covid: 0,
                };


            let corp_wth_tax = $('#cop_wth option:selected').data('content');
            let vat_wth_tax = $('#vat_wth option:selected').data('content');
            let fixed_vat_wth_tax = parseFloat($('#vat_wth_fixed').val()) ? parseFloat($('#vat_wth_fixed').val()) : 0;
            let fixed_corp_wth_tax = parseFloat($('#cop_wth_fixed').val()) ? parseFloat($('#cop_wth_fixed').val()) : 0;

            // Go through list items
            $.each(product_rows, function (i, row_content){

                console.log("product_rows ", product_rows);
                // Compute subtotal of each list item

                sub_total = row_content.quantity * row_content.unit_price;
                net_total = sub_total + row_content.tax_amount;

                let new_sub_total = sub_total;
                let new_net_total = sub_total;

                // Loop through selected taxes
                if(row_content.selected_tax_ids_from_grid !== undefined)
                {
                    $.each(row_content.selected_tax_ids_from_grid, function (i, selected_tax_id) {

                        //find the tax item
                        let selected_tax = fetched_taxes.find(tax_data => tax_data.id === Number(selected_tax_id))

                        // Compute for individual tax items
                        if(selected_tax !== undefined) {

                            if (selected_tax.name.toLowerCase().includes('covid 19')) {
                                item_type = 'COVID 19 TAX';
                                tax_percentage.covid = Number(selected_tax.percentage)
                                tax_values.covid = (new_sub_total * tax_percentage.covid) / 100
                                tax_total.covid += tax_values.covid;
                            }

                            if (selected_tax.name.toLowerCase() === 'flat vat') {
                                item_type = 'Flat VAT';
                                tax_percentage.flat_vat = Number(selected_tax.percentage);
                                tax_values.flax_vat = (new_sub_total * tax_percentage.flat_vat) / 100
                                tax_total.flax_vat += tax_values.flax_vat;
                            }

                            if (selected_tax.name.toLowerCase() === 'nhil') {
                                item_type = 'NHIL';
                                tax_percentage.nhil = Number(selected_tax.percentage);
                                tax_values.nhil = (new_sub_total * tax_percentage.nhil) / 100
                                tax_total.nhil += tax_values.nhil;
                            }

                            if (selected_tax.name.toLowerCase().includes('getfund')) {
                                item_type = 'GETFund';
                                tax_percentage.getfund = Number(selected_tax.percentage);
                                tax_values.getfund = (new_sub_total * tax_percentage.getfund) / 100
                                tax_total.getfund += tax_values.getfund
                            }

                            if (selected_tax.name.toLowerCase().includes('cst')) {
                                item_type = 'CST';
                                tax_percentage.cst = Number(selected_tax.percentage);
                                tax_values.cst = (new_sub_total * tax_percentage.cst) / 100
                                tax_total.cst += tax_values.cst
                            }

                            math.getfund += tax_total.getfund;
                            math.nhil    += tax_total.nhil;
                            math.cst += tax_total.cst;
                            math.covid += tax_total.covid;
                            math.vat += tax_total.flax_vat + tax_total.vat_standard;

                        }
                    })
                }

                math.sub += parseFloat(sub_total);  //sub total

                let new_selected_tax_id = row_content.selected_tax_ids_from_grid.find(vat_id => vat_id === '1');

                let net_tax = fetched_taxes.find(tax_data => tax_data.id === Number(new_selected_tax_id))

                if(net_tax !== undefined){

                    tax_percentage.vat = Number(net_tax.percentage);

                    tax_values.vat = ((tax_values.nhil + tax_values.covid + tax_values.getfund + tax_values.cst + new_sub_total) * tax_percentage.vat) / 100

                    tax_total.vat += tax_values.vat;
                }

            })

            // $('#all_net').val(net.toFixed(2));
            // $('#tax_total').val((math.vat + math.getfund + math.cst + math.nhil + math.covid).toFixed(2));

            // Change frontend items (blade file)
            $('#nhil_total').val(Number(tax_total.nhil).toFixed(2))
            $('#cst_total').val(Number(tax_total.cst).toFixed(2));
            $('#getfund_total').val(Number(tax_total.getfund).toFixed(2));
            $('#vat_total').val(Number(tax_total.vat).toFixed(2))
            $('#covid_total').val(Number(tax_total.covid).toFixed(2));
            $('#vat_flat_total').val(Number(tax_total.flax_vat).toFixed(2))

            let total_tax = (Number(tax_total.nhil) + Number(tax_total.cst) + Number(tax_total.getfund) + Number(tax_total.vat) + Number(tax_total.covid) + Number(tax_total.flax_vat));
            $('#all_tax').val(Number(total_tax).toFixed(2))
            net = (math.sub + total_tax);
            $('#all_net').val(net.toFixed(2));
        }


        function RedoTotalCalculations(id = null)
        {
            $.each(product_rows, function (i, row_content){

                if(row_content.selected_tax_ids_from_grid !== undefined)
                {
                    populateRowTax(row_content.row_id, row_content.selected_tax_ids_from_grid)
                }
            })

            if(id != null){
                let get_id = '#'+id;
                if($(get_id).val() != ''){
                    strLength = $(get_id).val().length * 2;
                    changeAttr(get_id, strLength);
                }

            }

        }

        $('#vat_wth').on('change', function (){
            RedoTotalCalculations();
        });

        $('#cop_wth').on('change', function (){
            RedoTotalCalculations();
        });

        function deleteRow(row_id)
        {
            // delete the process flow from the position
            product_rows = product_rows.filter(row_data => row_data.row_id !== Number(row_id))

            //reset the ids actions
            $.each(product_rows, (i, row_data) => {

                row_data.row_id = i +1;

                row_data.row_action = `<td>
                                            <a href="javascript:void(0);"  title="${row_data.row_id}" class="btn btn-icon shadow btn-danger btn-sm btn-circle mr-2 convert del_row"><i class="fa fa-sm fa-trash"></i></a>
                                        </td>
                                     </tr>`
            })

            displayRowItems()
        }

        function changeAttr(element, length){
            $(element).attr('type', 'text');
            $(element).focus();
            $(element)[0].setSelectionRange(length, length);
            $(element).attr('type', 'tel');
            $(element).focus();
        }

        function checkDiscountType(sub_total,row_content=null)
        {

            let data = {'discount_amount': 0, 'interest_amount': 0};

            if (row_content.discount_interest_ === 'discount'){
                if(row_content.discount_interest_type_ === 'percentage')
                {
                    data.discount_amount = (Number(row_content.discount_interest_value_) * sub_total) / 100

                }else if(row_content.discount_interest_type_ === 'amount') {

                    data.discount_amount = Number(row_content.discount_interest_value_)
                }
            }
            else if(row_content.discount_interest_ === 'interest'){
                if(row_content.discount_interest_type_ === 'percentage')
                {
                    data.interest_amount = (Number(row_content.discount_interest_value_) * sub_total) / 100

                }else if(row_content.discount_interest_type_ === 'amount') {

                    data.interest_amount = Number(row_content.discount_interest_value_)
                }
            }

            return data;
        }


        function setProductOptions(selected_product_id=null, item_type=null)
        {

            let product_options = ``

            $.each(fetched_products, function(i, product)
            {
                if(selected_product_id !== null)
                {
                    let product_selected = (product.id === selected_product_id) ? 'selected' : ''

                    product_options += `<option ${product_selected} title="${item_type}" value="${product.id},${item_type}">${product.model ? product.model+' ('+product.car_number+')' : product.car_number}</option>`
                }else{

                    product_options += `<option value="${product.id},${item_type}" title="${item_type}">${product.model ? product.model+' ('+product.car_number+')' : product.car_number}</option>`
                }

            })

            // console.log(product_options)

            return product_options
        }

        function setTaxOptions(selected_tax_id=null)
        {
            tax_options = ``

            $.each(fetched_taxes, function(i, tax)
            {
                if(selected_tax_id !== null)
                {
                    let tax_selected = tax.id === Number(selected_tax_id) ? 'selected' : ''

                    tax_options += `<option ${tax_selected} value="${tax.id}">${tax.name}</option>`

                }else{

                    tax_options += `<option value="${tax.id}">${tax.name}</option>`
                }
            })

            return tax_options
        }

        function setDiscountOptions(selected_option=null)
        {
            discount_options = ``

            $.each(discount_array, function(i, option)
            {
                if(selected_option !== null)
                {
                    let option_selected = option.value === selected_option ? 'selected' : ''

                    discount_options += `<option ${option_selected} value="${option.value}">${option.name}</option>`

                }else{

                    discount_options += `<option value="${option.value}">${option.name}</option>`
                }
            })

            return discount_options
        }

        function setDiscountTypes(selected_type=null)
        {
            discount_types = ``

            $.each(discount_types_array, function(i, option)
            {
                if(selected_type !== null)
                {
                    let option_selected = option.value === selected_type ? 'selected' : ''

                    discount_types += `<option ${option_selected} value="${option.value}">${option.name}</option>`

                }else{

                    discount_types += `<option value="${option.value}">${option.name}</option>`
                }
            })

            return discount_types
        }

        function addRow()
        {
            let new_row_id = product_rows.length + 1

            let product_options = setProductOptions()

            let tax_options = setTaxOptions()

            let discount_options = setDiscountOptions()

            let discount_types = setDiscountTypes()

            product_rows.push({
                product_options,
                discount_options,
                discount_types,
                amount : ``,
                quantity: '',
                discount_interest_value_: '',
                discount_interest_: '',
                discount_interest_type_: '',
                row_id: new_row_id,
                description: '',
                tax_options,
                unit_price: ``,
                selected_tax_ids_from_grid: [],
            })

            displayRowItems()
        }

        function displayRowItems()
        {
            let main_dom = ``

            let all_data_with_tax = {
                sub_total: 0,
                taxes: 0,
                grand_total: 0,
                discounted_amount: 0,
                interested_amount: 0,
            }

            $.each(product_rows, function (i, row_content){

                if(row_content.selected_product_id !== undefined)
                {
                    row_content.product_options = setProductOptions(row_content.selected_product_id, item_type)
                }

                if(row_content.selected_tax_ids_from_grid !== undefined)
                {

                    if((row_content.selected_tax_ids_from_grid !== null ? row_content.selected_tax_ids_from_grid.length : 0))
                    {
                        row_content.tax_options = MarkTaxesAsChecked(row_content.selected_tax_ids_from_grid, row_content)

                    }else{
                        row_content.tax_options = setTaxOptions(row_content.selected_tax_id)
                    }
                }

                let new_row_content = gridSkeleton(row_content)

                main_dom += new_row_content

                all_data_with_tax.sub_total += Number(row_content.amount)

                if(row_content.apply_row_discount)
                {
                    all_data_with_tax.discounted_amount += Number(checkDiscountType(row_content.amount,row_content).discount_amount)

                    all_data_with_tax.interested_amount += Number(checkDiscountType(row_content.amount,row_content).interest_amount)

                }

                if(row_content.tax_amount !== undefined)
                {
                    all_data_with_tax.taxes += row_content.tax_amount
                }

                {{--row_content.account_id = '{{\App\Account::query()->where('name', 'like', 'Purchases')->first()->id ?? 0}}'--}}
            })

            $('#tbody').html(main_dom)

            styleSelectTags()

            $('#all_sub_total').val(all_data_with_tax.sub_total)

            $('#all_discount_amount').val(all_data_with_tax.discounted_amount)

            $('#all_interest_amount').val(all_data_with_tax.interested_amount)

            // $('#all_tax').val(all_data_with_tax.taxes)

            let net_total = ((all_data_with_tax.sub_total - all_data_with_tax.discounted_amount) + (all_data_with_tax.taxes + all_data_with_tax.interested_amount))

            $('#all_net').val(parseFloat(net_total).toFixed(2))

            //delete a row from a quotation table list
            $('.del_row').click(function(){

                let selected_row_id = $(this).attr('title')

                deleteRow(selected_row_id)

                GetIndividualTaxes();
            })

            $('.selected_grid_product').change(function() {

                let selected_product_id_main = $(this).children("option:selected").val();

                let selected_product_id = selected_product_id_main.split(',');
                selected_product_id = selected_product_id[0];

                let row_id = $(this).attr('title')

                let findProduct = fetched_products.find(x => String(x.id) === selected_product_id);

                if(findProduct === undefined) {
                    let new_prod = {
                        'currency_id': 1,
                        'description': selected_product_id,
                        'model': selected_product_id,
                        'car_cost': 1,
                        'id': selected_product_id,
                        'item_type': "car",
                        'name': selected_product_id,
                        'price': 1,
                        'cost_price': 1,
                        'unit_price': 1,
                        'purchase_price': 1,
                        'quantity': 1,
                        'selling_price': 1,
                        'tax_id': null,
                    };
                    fetched_products.push(new_prod);
                }

                waitme('card_content');

                setTimeout(function() {
                    hidewaitme('card_content');
                    populateFormRow(row_id, selected_product_id_main)
                    GetIndividualTaxes();
                }, 500);
            });

            $('.selected_grid_tax').change(function() {

                // let selected_product_id = $(this).children("option:selected").val();

                let selected_tax_ids = $(this).children("option:selected").toArray().map(item => item.value);

                let row_id = $(this).attr('title')

                displayRowItems()

                populateRowTax(row_id, selected_tax_ids)

                GetIndividualTaxes();

            });

            $('.apply_discount').click(function() {

                let row_id = $(this).attr('title')

                if($(this).is(':checked'))
                {
                    displayRowItems()

                    calculateDiscount(row_id)

                }else{
                    reCalculateDiscount(row_id)
                }
            })

            $('.input_quantity').on('keyup', function(){

                let input_item_quantity = $(this).val()

                let row_id = $(this).attr('title'),
                    item_id = $(this).attr('id');

                updateItemQuantity(row_id, input_item_quantity)

                RedoTotalCalculations(item_id);

                GetIndividualTaxes();


            })

            $('.input_quantity').on('change', function(){

                let input_item_quantity = $(this).val()

                let row_id = $(this).attr('title'),
                    item_id = $(this).attr('id');

                updateItemQuantity(row_id, input_item_quantity)

                RedoTotalCalculations(item_id);

                GetIndividualTaxes();

            })

            $('.item_unit_price').keyup(function(){

                let item_unit_price = $(this).val(),
                    item_id = $(this).attr('id');

                if(Number(item_unit_price) === 0)
                {
                    initErrorAlert('Unit Price can\'t be 0')

                }else{
                    let regexpInt =/^\d+$/;
                    let regexpFloat = /^\d+\.\d{0,2}$/;
                    // if ( !(regexpInt.test($(this).val().toString()) || regexpFloat.test($(this).val().toString())) ){
                    //     $(this).val($(this).val().toString().slice(0,-1));
                    //     initErrorAlert('Unit Price must be digits')
                    //     return false;
                    // }

                    input_price.row_id = $(this).attr('title')

                    input_price.unit_price = item_unit_price;

                    RedoTotalCalculations(item_id);

                    GetIndividualTaxes();

                    $(this).focus();

                }

            })

            $('.discount_interest_value').blur(function(){

                let value = $(this).val();
                let row_id = $(this).attr('title');

                discount_interest_value.row_id = row_id

                discount_interest_value.value = value

                let current_row_content = product_rows.find(row_data => row_data.row_id === Number(row_id))

                if (current_row_content.apply_row_discount){
                    calculateDiscount(row_id)
                }
                displayRowItems()
            })

            $('.discount_interest').change(function() {

                let type = $(this).val();

                let row_id = $(this).attr('title');

                discount_interest.row_id = row_id

                discount_interest.type = type

                let current_row_content = product_rows.find(row_data => row_data.row_id === Number(row_id))

                if (current_row_content.apply_row_discount){
                    calculateDiscount(row_id)
                }
                displayRowItems()
            });

            $('.discount_interest_type').change(function() {

                let type = $(this).val();

                let row_id = $(this).attr('title');

                discount_interest_type.row_id = row_id

                discount_interest_type.type = type

                let current_row_content = product_rows.find(row_data => row_data.row_id === Number(row_id))

                if (current_row_content.apply_row_discount){
                    calculateDiscount(row_id)
                }
                displayRowItems()
            });

            $('.description').keyup(function(){

                input_description.description = $(this).val()

                input_description.row_id = $(this).attr('title')

            })
        }


        function MarkTaxesAsChecked(selected_tax_ids_from_grid, current_row_content)
        {
            tax_options = ``

            let selected_tax_name = []
            $.each(fetched_taxes, function (i, tax) {

                $.each(selected_tax_ids_from_grid, function(x, selected_tax_id)
                {
                    if(tax.id === Number(selected_tax_id))
                    {
                        console.log(tax, selected_tax_id)

                        selected_tax_name.push(tax.name)

                        tax_options += `<option selected value="${tax.id}">${tax.name}</option>`
                    }
                })
            })

            $.each(fetched_taxes, function (i, tax) {

                if(!selected_tax_name.some(tax_name => tax_name === tax.name))
                {
                    // console.log(tax)
                    tax_options += `<option value="${tax.id}">${tax.name}</option>`
                }
            })

            return tax_options
        }


        function populateFormRow(row_id, selected_product_id_from_grid)
        {

            let product_id = selected_product_id_from_grid.split(',')[0]

            let item_type = selected_product_id_from_grid.split(',')[1]

            //get the product using the selected product id
            let product = fetched_products.find(row_data => String(row_data.id) === product_id)

            console.log('selected product {}', product_id)

            //get the content of row from which the product was selected
            let current_row_content = product_rows.find(row_data => row_data.row_id === Number(row_id))

            //set subtotal for the row content
            current_row_content.amount = product.car_cost !== null ? product.car_cost : 1

            // current_row_content.amount = product.sub_total
            // current_row_content.total = product.sub_total

            //set input quantity for the row content
            current_row_content.quantity = 1

            current_row_content.description = product.model+' ('+product.car_number+')'

            // current_row_content.unit_price = product.price
            current_row_content.unit_price = product.car_cost !== null ? product.car_cost : 1

            current_row_content.selected_product_id = product.id

            current_row_content.max_quantity = 1

            current_row_content.item_type = item_type

            current_row_content.apply_row_discount = false

            current_row_content.selected_tax_ids_from_grid = []

            current_row_content.total = product.car_cost !== null ? product.car_cost : 1

            displayRowItems()
        }

        function gridSkeleton(row_data)
        {
            if(Number(row_data.row_id) === Number(input_description.row_id))
            {
                row_data.description = input_description.description

                input_description.row_id = 0

                input_description.description = ''
            }

            if(Number(row_data.row_id) === Number(input_price.row_id))
            {
                row_data.unit_price = input_price.unit_price

                input_price.unit_price = 0

                input_price.row_id = 0

                row_data.amount = row_data.unit_price * row_data.quantity
            }
            else{
                row_data.amount = row_data.unit_price * row_data.quantity
            }

            if(Number(row_data.row_id) === Number(discount_interest_value.row_id))
            {
                row_data.discount_interest_value_ = discount_interest_value.value

                discount_interest_value.value = 0

                discount_interest_value.row_id = 0

            }


            if(Number(row_data.row_id) === Number(discount_interest.row_id))
            {
                row_data.discount_interest_ = discount_interest.type

                row_data.discount_options= setDiscountOptions(discount_interest.type)

                discount_interest.type = ''

                discount_interest.row_id = 0
            }

            if(Number(row_data.row_id) === Number(discount_interest_type.row_id))
            {
                row_data.discount_interest_type_ = discount_interest_type.type

                row_data.discount_types= setDiscountTypes(discount_interest_type.type)

                discount_interest_type.type = ''

                discount_interest_type.row_id = 0
            }

            let description = row_data.description === '' || row_data.description === null ? `` : row_data.description;

            let unit_price = row_data.unit_price === '' ? `` : row_data.unit_price;

            let discount_value = row_data.discount_interest_value_ === '' ? `` : row_data.discount_interest_value_;

            let subtotal = row_data.amount === '' ? `` : row_data.amount;

            let net_total = row_data.amount === '' ? `` : row_data.amount;

            let quantity = ``

            let row_discount = ``

            if(row_data.item_type === 'service')
            {
                quantity = `<input type="text" id="item_unit_quantity_${row_data.row_id}" style="min-width:110px" value="1" readonly name="quantity" class="form-control input_quantity" autofocus>`

            }
            else{
                quantity = `<input type="number" id="item_unit_quantity_${row_data.row_id}" style="min-width:110px" title="${row_data.row_id},${item_type}"  value="${row_data.quantity}" min="1" class="form-control input_quantity">`
            }

            return `<tr>
                            <td class="text-center">
                                <select name="product" title="${row_data.row_id}" class="select selected_grid_product" style="min-width:170px; height: 30px; font-size: 12px !important;">
                                        <option value="">-- select/type service --</option>
                                        ${row_data.product_options}
                                    </select>
                                </td>
                                <td class="text-center"><input class="form-control description" title="${row_data.row_id}" style="font-size: 12px; min-width:150px;" value="${description}"></td>
                                <td class="text-center"><input type="tel" id="item_unit_price_${row_data.row_id}" style="min-width:110px; font-size: 12px;" class="form-control item_unit_price" title="${row_data.row_id}" min="0"  value="${unit_price}" step="0.01"></td>
                                <td class="text-center">${quantity}</td>
                                <td class="text-center">
                                    <select style="min-width:150px" class="select mt-3 selected_grid_tax" multiple="" title="${row_data.row_id}">
                                        <option value="">-- select tax --</option>
                                        ${row_data.tax_options}
                                    </select>
                                </td>

                                <td class="text-center"><input style="min-width:110px; font-size: 12px;" type="number" disabled class="form-control" value="${subtotal}"</td>
                                <td class="text-center">
                                    <a href="javascript:void(0);"  title="${row_data.row_id}" class="btn btn-icon shadow btn-danger btn-sm btn-circle mr-2 convert del_row"><i class="fa fa-sm fa-trash"></i></a>
                                </td>
                            </tr>
                           `;
        }

        // on save: retrieve grid and submit
        $('#formInvoice').submit(function (e){
            e.preventDefault();
            let vendor_id = $('#vendor_id').val()
            let invoice_number_type = $('#invoice_number_type option:selected').val();
            let invoice_number = $('#invoice_number').val();
            let due_date = $('#due_date').val()
            let reference = $('#reference').val()
            let message = $('#message').val()
            let sub_total = $('#all_sub_total').val()
            let total_applied_tax = $('#all_tax').val()
            let net_total = $('#all_net').val()
            let nhil_total = $('#nhil_total').val();
            let cst_total = $('#cst_total').val();
            let getfund_total = $('#getfund_total').val();
            let vat_total = $('#vat_total').val();
            let covid_total = $('#covid_total').val();
            let vat_flat_total = $('#vat_flat_total').val();
            let maintenance_id = '{{request()->segment(count(request()->segments()))}}';

            let data = {
                vendor_id,
                invoice_number_type,
                invoice_number,
                due_date,
                reference,
                message,
                sub_total,
                total_applied_tax,
                net_total,
                nhil_total,
                cst_total,
                getfund_total,
                vat_total,
                covid_total,
                vat_flat_total,
                product_rows,
                maintenance_id
            }

            if (invoice_number_type === 'manual' && invoice_number === '') {
                initErrorAlert('Invoice Number can\'t be empty');
                return;
            }

            if (product_rows.length === 0) {
                initErrorAlert('Row items in the grid can\'t be empty');
                return;
            }

            console.log('this is the sending items', data);

            waitme('formInvoice');
            $.ajax({
                url: '{{ route('finance.invoice.store') }}',
                method: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data_type: 'json',
                data,
            }).done((response) => {
                console.log('This is the ajax response', response)
                hidewaitme('formInvoice')
                if (response.message === 'success') {

                    window.location = '{{route('mechanic.garage')}}'

                }
                else {

                    let errors = `${response.message}`

                    if(errors === 'Invoice Items can\'t be empty. Please select one or more line items')
                    {
                        $('#errorMsg').html(errors)

                    }
                    else if(errors === 'error'){
                        $('#errorMsg').html('Whoops! Something went wrong')

                    }else {

                        let error_dom = ``

                        if(typeof(response.errors) === 'string')
                        {
                            error_dom += `${error}<br>`
                        }else{

                            $.each(response.errors, (i, error)=> {

                                error = error.replace('[', '')
                                error = error.replace(']', '')

                                error_dom += `${error}<br>`
                            })
                        }

                        $('#errorMsg').html(error_dom)
                    }
                    $('#errorMsg').show();
                    //
                    $('#errorMsg').fadeOut(15000)
                }
            })
        })
    })

</script>
