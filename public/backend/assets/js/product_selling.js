class ImportDoc {
    constructor(id, customer_id, final_amount, discount_amount,shipcost,paid_amount, bank_id,wh_id,delivery_id) {
    this.id = id;
      this.customer_id = customer_id;
      this.final_amount = final_amount;
      this.discount_amount = discount_amount;
      this.shipcost = shipcost;
      this.paid_amount = paid_amount;
      this.bank_id = bank_id;
      this.wh_id = wh_id;
      this.delivery_id = delivery_id;
     
    }
 
}

class Pricelist{
    constructor(id,title, price,gpid){
        this.id = id;
        this.title = title;
        this.price = price;
        this.gpid = gpid;
    }
    updatePrice(newPrice) {
        this.price = newPrice;
    }
    generateHTMLLI()
    {
        var myhtml = ' <li>    ';
        myhtml += ' <div  > <button style="btn btn-primary  mr-5"   onclick="updateGroupPrice('+this.gpid+')" class="ipprice py-1 px-2  text-right form-control">'
         +this.title+" : "+ this.price+' </botton> <input type="hidden" value="'
         +this.price +'" id="gp'  +this.gpid+'"/></div>';
        myhtml +='</li>';
        return myhtml;
    }
    generateHTML()
    {
        var myhtml = '';
        myhtml = ' <div  class="col-span-2"><labe class="form-select-label">'
        +this.title+'</label><input style="border:1px solid grey" id="gp'
        +this.gpid+'" onchange="updateGroupPrice('+this.gpid+')" class="ipprice py-1 px-2  text-right form-control"'
        +'value="'+ this.price+'"/>  </div>';
        return myhtml;
    }
}
class Product {
    constructor(id,name, price,type, quantity,stock_quantity,url,seri,series,pricelist) {
        this.id = id;
      this.name = name;
      this.url = url;
      this.price = price;
      this.type = type;
      this.quantity = quantity;
      this.stock_quantity = stock_quantity;
      this.pricelist = pricelist;
      this.seri = seri;
      this.series = series.split(","); 
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
        // var divprice = ' <div id="div_group_price"  >'
        //     +' <h2> Giá bán: </h2>'
        //     +' <div style="font-size:80%" class="overflow-x-auto flex grid grid-cols-12 gap-2" id="t-proid" >';
      
        // this.pricelist.forEach((pp) => {
        //     divprice+= pp.generateHTML();
           
        // });
        // divprice += '</div></div>';
        var dropdown = '<div class="dropdown py-3 px-1 "> '
        +'<a style=" cursor: pointer; " aria-expanded="false" '
        + 'data-tw-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="chevron-down" data-lucide="chevron-down" class="lucide lucide-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></a>'
        + '<div class="dropdown-menu w-40">'
        +    '<ul class="dropdown-content"><li>Chọn giá:</li>';
        if(this.pricelist)
        {
            this.pricelist.forEach((pp) => {
                dropdown+= pp. generateHTMLLI();
            });
        }
        dropdown+=    '</ul></div> </div>';
        
   
        var btnclose='<button type="button" onclick="removeProduct('+this.id+')" class="btn-close text-red"   aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" data-lucide="x" class="lucide lucide-x w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> </button>';
        var tonkho = "";
        if(this.type=="normal")
        {
            tonkho = this.stock_quantity+ '';
            
        }
        else
        {
            dropdown = '';
            tonkho = "dịch vụ";
        }
        
        var seri_tag ='<div class="flex"><label for="options">Số seri:</label>'
        + '<select id="options'+this.id+'" onchange="addToInput('+this.id+')"><option>--chọn --</option>';
        this.series.forEach((pp) => {
            seri_tag += '<option value="'+pp+'">'+pp+'</option>';
        });
        seri_tag += '</select><input style="width:100%" onchange="updateQuantityS('+this.id+')" type="text" id="seri'
        +this.id+'" value="'+this.seri+'"></div>';
    
        

        var myhtml = '<tr><td class="!py-4"><div class="flex items-center"><div class="w-10 h-10 image-fit zoom-in">'
        + '<img class="rounded-lg border-2 border-white shadow-md tooltip" src="'
        + this.url + '" ></div><div><a href="#" class="font-medium   ml-2">'
        + this.name+'</a> <br/><span class="form-help px-2"> Tồn kho: '+ tonkho+'</span></div> </div> </td> <td class="text-right"><div class="flex"><input type="number" style="width:150px" id="ip'
        +this.id+'" onchange="updatePrice('+this.id+')" class="ipprice py-3 px-4  text-right form-control  " value="'
        + this.price+'"/> '+ dropdown+' </div></td><td class="text-right"><input type="number" style="width:100px"  id="iq'
        + this.id +'" onchange="updateQuantity('+this.id+')" class="ipqty py-3 px-4  text-right form-control " value="'
        + this.quantity+'"/></td><td class="text-right">'
        + Intl.NumberFormat().format(this.getTotalCost())+'</td><td>' + btnclose +'</td></tr>'
        // +'<tr> <td colspan="5">'+ divprice+' </td></tr>'
        +'<tr> <td colspan="5"> '+ seri_tag + '</td></tr>'
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
  function addToInput(id) {
    var select = document.getElementById("options"+id);
    var input = document.getElementById("seri"+id);
    var selectedOption = select.options[select.selectedIndex].value;
    if(input.value.trim() != '')
        input.value +=  "," + selectedOption ;
    else
        input.value +=  selectedOption  ;
    updateQuantityS(id);
}
  function updatePrice(id )
{
    ip = document.getElementById('ip'+id);
    // alert(ip.value);
    if(ip.value < 0)
    {
        ip.value = 0;
    }
    productList.forEach((product) => {
        if(product.id == id)
        {
            product.updatePrice(ip.value);
           
        }
    });
    updateListView();
}
function updateGroupPrice(id)
{
    ip = document.getElementById('gp'+id);
    if(ip.value < 0)
    {
        ip.value = 0;
    }
    productList.forEach((product) => {
        product.pricelist.forEach((pp) => {
            if(pp.gpid == id)
            {
                pp.updatePrice(ip.value);
                // alert(ip.value);
                product.price = ip.value;
                priceip = document.getElementById('ip'+product.id);
                priceip.value = ip.value;
            }
        });
        
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
   
    if(ip.value < 0)
    {
        Swal.fire(
            'Không hợp lệ!',
            'Số lượng phải lớn hơn 0!',
            'error'
        );
        ip.value = 1;          
    }
   
    productList.forEach((product) => {
        if(product.id == id)
        {
            
            if(product.type == 'normal')
            {
                if(ip.value*1 > product.stock_quantity*1)
                {
                     
                    Swal.fire(
                        'Không hợp lệ!',
                        'Số lượng lớn hơn số lượng tồn kho!',
                        'error'
                    );
                    ip.value = 1;
                    product.updateQuantity(ip.value);
                }
                else
                    product.updateQuantity(ip.value);
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
                
                if(product.type == 'normal')
                {
                    if(num*1 > product.stock_quantity*1)
                    {
                        
                        Swal.fire(
                            'Không hợp lệ!',
                            'Số lượng lớn hơn số lượng tồn kho!',
                            'error'
                        );
                        num = 1;
                        product.updateQuantity(num);
                        product.seri = result;
                    }
                    else
                    {
                        product.updateQuantity(num);
                        product.seri = result;
                    }    
                }
                else
                {
                    product.updateQuantity(num);
                    product.seri = '';
                }    
            }
        });
    }
    updateListView();
}

function generateTableFooter()
{
    var pcount = 0;
    var qsum = 0;
    var ptotal = 0;
    productList.forEach((product) => {
        pcount ++;
        qsum += product.quantity*1;
        ptotal += product.getTotalCost();
    });
    var myhtml = "<tr> <td class='text-left'>Tổng số loại: "+pcount 
            +"</td><td class='text-right'>-</td><td class='text-right'> số lượng: "
            +qsum+"</td><td class='text-right' colspan='2'> tổng tiền: "
            + Intl.NumberFormat().format(ptotal) +"</td></tr>";
    if(tong!=null)
        tong = ptotal;
    var iptotalcost = document.getElementById('totalcost');
    var shipcost = document.getElementById('shipcost');
    var discount_amount = document.getElementById('discount_amount');
    var sptotalcost = document.getElementById('sptotalcost');

    if(iptotalcost)
     {
        iptotalcost.value = ptotal -discount_amount.value + shipcost.value*1;
        var tt = iptotalcost.value * 1;
        sptotalcost.innerHTML = Intl.NumberFormat().format( tt);
        if (document.getElementById('check_paidall').checked) {
            document.getElementById('paid_amount').value = iptotalcost.value;
        }
     }   
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
    var product_search = $('#product_search');
    product_search.val('');
    var myhtml ="";
    productList.forEach((product) => {
        
        
        myhtml += product.generateHTML();
    });
    tbody.html(myhtml);
    tfooter.html(generateTableFooter());
}