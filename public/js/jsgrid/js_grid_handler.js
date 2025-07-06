class GridHandler {
    constructor(products, taxes) {
        this.products = products;
        this.taxes = taxes;
        this.product_names = this.names();
    }

    names() {
        let names = [];
        for (let i = 0; i < this.products.length; i++) {
            names.push(this.products[i].name);
        }
        return names;
    }

    get_product(value) {
        for (let i = 0; i < this.products.length; i++) {
            if (this.products[i].id === parseInt(value) || this.products[i].name === value) {
                return this.products[i];
            }
        }
        return null;
    }

    get_tax(id) {
        for (let i = 0; i < this.taxes.length; i++) {
            if (this.taxes[i].id === parseInt(id))
                return this.taxes[i];
        }
        return 0;
    };

    tax_error_alert(args) {
        if (args.item.taxed) {
            if (args.item.vat > 0 && (args.item.getfund === 0 && args.item.nhil === 0)) {
                initErrorAlert("Please select taxes for GEFUND, NHIL AND VAT Standard!");
                // alert("ERROR: Please select taxes for GEFUND, NHIL AND VAT Standard!");
                args.cancel = true;
            } else {
                if (args.item.vat_flat === 0 && args.item.vat === 0) {
                    initErrorAlert("Please select either VAT Flat or VAT Standard");
                    // alert("ERROR: Please select either VAT Flat or VAT Standart");
                    args.cancel = true;
                }
            }
        }
    }

    on_item_inserting(args) {
        if (args.item.product_id === "" && args.item.account_id === 0) {
            initErrorAlert("Invalid Data Provided!");
            // alert("ERROR: Invalid a Data Provided!");
            args.cancel = true;
        }

        console.log(args.item);
        this.tax_error_alert(args);
        let item = this.get_product(args.item.product_id),
            vat = this.get_tax(parseInt(args.item.vat)),
            getfund = this.get_tax(parseInt(args.item.getfund)),
            nhil = this.get_tax(parseInt(args.item.nhil)),
            total_tax =
                (vat.percentage / 100) * (item.price * args.item.quantity) +
                (getfund.percentage / 100) * (item.price * args.item.quantity) +
                (nhil.percentage / 100) * (item.price * args.item.quantity);

        // set columns
        args.item.price = item.price.toFixed(4);
        args.item.total = (item.price * args.item.quantity + total_tax).toFixed(4);
    }


    on_item_updating(args) {
        if (args.item.product_id === 0 && args.item.account_id === 0) {
            initErrorAlert('Invalid Data Provided!')
            // alert("ERROR: Invalid a Data Provided!");
            args.cancel = true;
        }
        this.tax_error_alert(args);
        let item = this.get_product(args.item.product_id),
            vat = this.get_tax(parseInt(args.item.vat)),
            getfund = this.get_tax(parseInt(args.item.getfund)),
            nhil = this.get_tax(parseInt(args.item.nhil)),
            total_tax =
                (vat.percentage / 100) * (item.price * args.item.quantity) +
                (getfund.percentage / 100) * (item.price * args.item.quantity) +
                (nhil.percentage / 100) * (item.price * args.item.quantity);

        // set columns
        args.item.price = item.price.toFixed(4);
        args.item.total = (item.price * args.item.quantity + total_tax).toFixed(4);
    }

    insert_template(data) {
        let product = this.get_product(data.product.insertControl.val()),
            quantity = parseInt(data.quantity.insertControl.val()),
            vat_standard = this.get_tax(parseInt(data.vat_standard.insertControl.val())),
            nhil = this.get_tax(parseInt(data.nhil.insertControl.val())),
            vat_flat = this.get_tax(parseInt(data.vat_flat.insertControl.val()));

        let price = 0, sub_total = 0, vat_flat_total = 0, tax_total = 0;

        if (product !== null && quantity >= 0) {
            price = product.price;
            sub_total = price * quantity;
        }
        //TODO: fix tal calculation errors
        if (data.taxable.insertControl.is(':checked')) {
            let nhil_getfund = ((vat_flat.percentage / 100) * sub_total) + ((nhil.percentage / 100) * sub_total);
            vat_flat_total = (vat_flat.percentage / 100) * sub_total;
            tax_total = ((vat_standard.percentage / 100) * (sub_total + nhil_getfund)) + parseFloat(vat_flat_total);
        }
        data.price.insertControl.val(price.toFixed(4));
        data.total.insertControl.val((sub_total + tax_total).toFixed(4));
    }

    edit_template(data) {
        let product = this.get_product(data.product.editControl.val()),
            quantity = parseInt(data.quantity.editControl.val()),
            vat_standard = this.get_tax(parseInt(data.vat_standard.editControl.val())),
            nhil = this.get_tax(parseInt(data.nhil.editControl.val())),
            vat_flat = this.get_tax(parseInt(data.vat_flat.editControl.val()));

        let price = 0, sub_total = 0, vat_flat_total = 0, tax_total = 0;

        if (product !== null && quantity >= 0) {
            price = product.price;
            sub_total = price * quantity;
        }
        //TODO: fix tal calculation errors
        if (data.taxable.editControl.is(':checked')) {
            let nhil_getfund = ((vat_flat.percentage / 100) * sub_total) + ((nhil.percentage / 100) * sub_total);
            vat_flat_total = (vat_flat.percentage / 100) * sub_total;
            tax_total = ((vat_standard.percentage / 100) * (sub_total + nhil_getfund)) + parseFloat(vat_flat_total);
        }
        data.price.editControl.val(price.toFixed(4));
        data.total.editControl.val((sub_total + tax_total).toFixed(4));
    }

    // calculate order
    // calculate(data, apply_discount = false) {
    calculate(data) {

        let math = {sub: 0, vat: 0, net: 0, getfund: 0, nhil: 0, vat_flat: 0, discount: 0};

        // calculate sub total + tax
        data.grid_items.forEach((item) => {
            //TODO: add vat flat to total vat
            let _sub = item.price * item.quantity;
            let vat_standard = 0.0, getfund = 0.0, nhil = 0.0, vat_flat = 0.0;

            if (item.taxed) {
                let _vat = this.get_tax(item.vat),
                    _getfund = this.get_tax(item.getfund),
                    _nhil = this.get_tax(item.nhil),
                    _vat_flat = this.get_tax(item.vat_flat);

                getfund = (_getfund.percentage / 100) * _sub;
                nhil = (_nhil.percentage / 100) * _sub;
                vat_flat = (_vat_flat.percentage / 100) * _sub;
                vat_standard = +(_vat.percentage / 100) * (_sub + getfund + nhil)
            }


            if (item.apply_discount)
                math.discount += GridHandler.calculateDiscount(data, _sub);

            math.sub += _sub;
            math.vat += vat_standard;
            math.vat_flat += vat_flat;
            math.getfund += getfund;
            math.nhil += nhil;
        });



        math.net = math.sub + math.vat + math.getfund + math.nhil + math.vat_flat - math.discount;

        // update output fields
        data.sub_total.val(math.sub.toFixed(4));
        if (data.vat_flat_total !== undefined) {
            data.vat_flat_total.val((math.vat_flat).toFixed(4));
            data.vat_total.val((math.vat).toFixed(4));
        } else {
            data.vat_total.val((math.vat + math.vat_flat).toFixed(4));
        }
        data.getfund_total.val(math.getfund.toFixed(4));
        data.nhil_total.val(math.nhil.toFixed(4));
        data.net_total.val(math.net.toFixed(4));
    };

    static calculateDiscount(data, amount = 0.0) {

        let discount = parseFloat(data.discount.val());
        if (data.discount_type.val().toLowerCase() === "fixed")
            return discount;
        if (data.discount_type.val().toLowerCase() === "percentage")
            return amount * (discount * 0.01);
        return 0.0
    }

    populate_fields(data, insertControl = true)  {
        if (insertControl) {
            let product = this.get_product(data.product.insertControl.val());
            data.avail_for_sale.insertControl.val(product.quantity);
            data.description.insertControl.val(product.description);
            if (product.shelf !== null)
                data.location.insertControl.val(product.shelf.name);
            else
                data.location.insertControl.val("");
        } else {
            let product = handler.get_product(data.product.editControl.val());
            data.avail_for_sale.editControl.val(product.quantity);
            data.description.editControl.val(product.description);
            if (product.shelf !== null)
                data.location.editControl.val(product.shelf.name);
            else
                data.location.editControl.val("");
        }
    }
}

window.GridHandler = GridHandler;

function initErrorAlert(msg){
    Swal.fire(
        'Error!',
        msg,
        'error'
    )
}
