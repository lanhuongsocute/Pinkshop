class ImportDoc {
    constructor(id, supplier_id,  shipcost , bank_id, delivery_id  ) {
        this.id = id;
        this.shipcost = shipcost;
        this.bank_id = bank_id;
        this.supplier_id = supplier_id;
        this.delivery_id = delivery_id;
     
    } 
 
}
 
class Product {
    constructor(id,name, price, quantity,stock_quantity,url ,seri) {
        this.id = id;
      this.name = name;
      this.url = url;
      this.price = price;
      this.quantity = quantity;
      this.stock_quantity = stock_quantity;
      this.seri = seri;
    }
  
    // Method to get the total cost of the product
    getTotalCost() {
      return this.price * this.quantity;
    }
  
    // Method to update the price of the product
    updatePrice(newPrice) {
      this.price = newPrice;
    }
    generateHTML()
    {
        var btnclose='<button type="button" onclick="removeProduct('+this.id+')" class="btn-close text-red"  aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" data-lucide="x" class="lucide lucide-x w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> </button>';
        var myhtml = '<tr><td class="!py-4"><div class="flex items-center"><div class="w-10 h-10 image-fit zoom-in">'
        + '<img class="rounded-lg border-2 border-white shadow-md tooltip" src="'
        + this.url + '" ></div><div><a href="#" class="font-medium   ml-2">'
        + this.name+'</a> <br/><span class="form-help px-2"> Tồn kho: '+ this.stock_quantity+'</span></div> </div> </td> '
        +' <td class="text-right"><input type="number" style="width:100px"  id="iq'
        + this.id +'" onchange="updateQuantity('+this.id+')" class="ipqty py-3 px-4  text-right form-control " value="'
        + this.quantity+'"/></td> <td>' + btnclose +'</td></tr>'
        +'<tr> <td colspan="5"><span> Seri: </span> <input id="seri' 
        + this.id +'" style="width:100%" type="text" '
        + ' value ="' + this.seri +'" onchange="updateQuantityS('+this.id+')"    >'
        +  '</input> </td></tr>'
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
  
  function updatePrice(id )
{
    ip = document.getElementById('ip'+id);
    // alert(ip.value);
    productList.forEach((product) => {
        if(product.id == id)
        {
            product.updatePrice(ip.value);
           
        }
    });
    updateListView();
}
 
function removeProduct(id)
{
    id = id * 1;
    productList = productList.filter(product => product.id - id !== 0);
    updateListView();
}
function updateQuantity(id )
{
    ip = document.getElementById('iq'+id);
    // alert(ip.value);
    productList.forEach((product) => {
        if(product.id == id)
        {
            if(ip.value > product.stock_quantity)
            {
                Swal.fire(
                    'Không hợp lệ!',
                    'Số lượng lớn hơn số lượng tồn kho!',
                    'error'
                );
            }
            else
                product.updateQuantity(ip.value);
        }
    });
    updateListView();
}
function cleanArray(arr) {
    // Remove empty values
    let noSpacesArray = arr.map(item => {
        if (typeof item === 'string') {
            return item.replace(/\s+/g, '');
        }
        return item;
    });
    let cleanedArray = noSpacesArray.filter(item => item !== null && item !== undefined && item !== '');
    // Remove duplicate values from the array
    let uniqueArray = [...new Set(cleanedArray)];
    return uniqueArray;
}
function arrayToString(arr) {
    return arr.join(', ');
}
function updateQuantityS(id )
{
    ip = document.getElementById('seri'+id);
    var num = 0;
    const myArray = ip.value.split(",");
    let cleanedArray = cleanArray(myArray);
    let result = arrayToString(cleanedArray);
    if(cleanedArray.length > 0)
    {
        num = cleanedArray.length;
        productList.forEach((product) => {
            if(product.id == id)
            {
                product.updateQuantity(num);
                product.seri = result;
            }
        });
    }    
    updateListView();
}
function generateTableFooter()
{
    var pcount = 0;
    var qsum = 0;
    productList.forEach((product) => {
        pcount ++;
        qsum += product.quantity*1;
        
    });
    var myhtml = "<tr> <td class='text-left'>Tổng số loại: "+pcount 
            +"</td> <td class='text-right' colspan='2'> số lượng: "
            +qsum+"</td> </tr>";
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
    
    var myhtml ="";
    productList.forEach((product) => {
        
        
        myhtml += product.generateHTML();
    });
    tbody.html(myhtml);
    tfooter.html(generateTableFooter());
}