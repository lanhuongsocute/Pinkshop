class ImportDoc {
    constructor(id, supplier_id, final_amount, discount_amount,shipcost,paid_amount, bank_id,wh_id) {
    this.id = id;
      this.supplier_id = supplier_id;
      this.final_amount = final_amount;
      this.discount_amount = discount_amount;
      this.shipcost = shipcost;
      this.paid_amount = paid_amount;
      this.bank_id = bank_id;
      this.wh_id = wh_id;
     
    }
 
}
 
class Product {
    constructor(id,name, price, quantity,url ) {
        this.id = id;
      this.name = name;
      this.url = url;
      this.price = price;
      this.quantity = quantity;
     
    }
  
    // Method to get the total cost of the product
    getTotalCost() {
      return this.price * this.quantity;
    }
  
 
    generateHTML()
    {
   
        var btnclose='<button type="button" onclick="removeProduct('+this.id+')" class="btn-close text-red" data-tw-dismiss="alert" aria-label="Close"> Xoá  </button>';
        var myhtml = '<tr><td  ><div class="flex items-center"> '
        + '<img  class="rounded-full" width="50" height="50"  src="'
        + this.url + '" > <a   class="     ">'
        + this.name+'</a> </div>  <td>' + btnclose +'</td></tr>'
        
        
        ;
        return myhtml;
    }
    // Method to update the quantity of the product
    updateQuantity(newQuantity) {
      this.quantity = newQuantity;
    }
  
    // Method to display product information
    displayInfo() {
    console.log(`Product Id: ${this.id}`);
      console.log(`Product Name: ${this.name}`);
      console.log(`Price: $${this.price}`);
      console.log(`Quantity: ${this.quantity}`);
      console.log(`Total Cost: $${this.getTotalCost()}`);
    }
  }
  
   
function removeProduct(id)
{
    productList = productList.filter(product => product.id !== id);
    updateListView();
}
 
function generateTableFooter()
{
    var pcount = 0;
    var qsum = 0;
    var ptotal = 0;
    productList.forEach((product) => {
        pcount ++;
        
    });
    var myhtml = "<tr> <td colspan='2' class='text-left'>Tổng số sản phẩm: "+pcount 
            +"</td> </tr>";
      
    return myhtml;
}
function addtoProductList(newpro )
{
    var kq = true;
    productList.forEach((product) => {
        if(product.id == newpro.id)
            kq = false;
    });
    if(kq == true)
    productList.push(newpro)
    return kq;
}

function updateListView()
{
    var tbody = $('#product_list_table');
    var tfooter = $('#table_footer');
    $('#product_search').val('');
    var myhtml ="";
    productList.forEach((product) => {
        
        
        myhtml += product.generateHTML();
    });
    tbody.html(myhtml);
    tfooter.html(generateTableFooter());
    var table = document.getElementById('dsspthem');
    if (productList.length > 0)
        {
            table.style.display = "block";
        }
        else
        {
            table.style.display = "none";
        }
   
}