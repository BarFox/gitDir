Readme for assign3
SUGGESTION: I would suggest to use Safari to grade this project, I also have tested project on Google Chrome.
WARNING: I use the UTC time zone in the website, so the order placed time recorded in the database is later than the local time.

the online link for assignment3 is http://cs-server.usc.edu:3399/assign3/login.php .
 I have created a new database for assign3, because I want to make the topic more interesting - an online video store.
So I also rewrite a new version of assign2 for the new database. for changing stuff in the new database, you should use the rewrite one. I put the new assign2 inside the submitted zip file named “assign2”. The the online link for new assign2 is http://cs-server.usc.edu:3399/assign3/assign2/login.php .

and you can view the mysql structure here: http://cs-server.usc.edu:3399/phpMyAdmin-4.0.10.10-english/index.php
username:root	password:1992 

username&password for assign3
I created 3 customers, you could create new ones.
username:ctycus		password:chentian
username:zwycus 	password:zhangwen
username:wyycus		password:wangya

username&password for new assign2
(employee)
	username:ctyemp		password:chentian
(admin)
	username:zwyemp 	password:zhangwen
(manager)
	username:wyyemp		password:wangya

1. mysql structure 
plus 5 table from assign2, there are 9 tables in the database now.
(1)customer:
customerid	int(11)	No 	 	 
customername	varchar(100)		 	 
customeraddress	varchar(300)	 	 	 
creditcard	bigint(20)		 	 
securitycode	int(11)	No 	 	 
expirationdate	varchar(100)		 	 
username	varchar(100)		 	 
password	varchar(100)

(2)employees
userindex	int(11)		 	 
employeeid	int(11)		 	 
employeefname	varchar(100)		 	 
employeelname	varchar(100)		 	 
age	int(11)	 	 	 
salary	float	

(3)orderhis
orderid	int(11)	 	 	 
orderdate	varchar(100)		 	 
customeraddress	varchar(300)		 	 
creditcard	bigint(20)	 	 	 
customerid	int(11)

(4)orderitems
orderid	int(11)		 	 
productid	int(11)	 	 	 
productquantity	int(11)		 	 
productprice	float

(5)orders( this is the shopping cart)
productid	int(11)		 	 
quantity	int(11)	 	 	 
customerid	int(11)

(6)product
productid	int(11)	 	 	 
productcategoryid	int(11)		 	 
productname	varchar(100)	 	 	 
productdesc	text	 	 	 
productprice	float		 	 
productimage	varchar(200)

(7)productcategory
productcategoryid	int(11)	 	 	 
productcategoryname	varchar(100)	 	 	 
productcategorydesc	text	

(8)specialsales
specialsalesid	int(11)	 	 	 
productid	int(11)	 	 	 
startdate	varchar(100)	 	 	 
enddate	varchar (100)	

(9)users
userindex	int(11)	 	 	 
username	varchar(100)	 	 	 
password	varchar(100)	 	 	 
usertype	varchar(100)	  	 	 
	
2. file structure
only introduce new files of assign3

signup.php: this is handling signup stuff.

login.php & postlogin.html & prelogin.html: this is handling login stuff.only php in login.php file, html stuff are inside pergola.html and posthtml.html.

search.php: this is the main page, after login, you could click the search button at the top of each page at any time, you jump to search page. it shows up products.

showP.php: show specific details about product. you could buy it here or add it into the shopping cart. 
WARNNING: my website is a online video store, it is rare that people want to buy more than one same product, so I don’t provide button in showP.php to change the quantity you buy. But well, You could change the quantity you want inside shopping cart after you add the product into it.

orders.php: this the shopping cart, store stuff customers put into the shopping cart. I decide not to store this using SESSIONs, cause I want customer could find their shopping cart next time they login in. you could jump to shopping cart any time by the top right button named “cart”; you could place product here.

ordersucc.php: this is the page showing customer their order has been successfully placed.this page handles several stuff: 1. update customer table, if people change their private information during placing a order. 2. insert a new order into orderitems &orderhis. 3. delete all stuff inside the shopping cart.

orderhis.php: this php showing all the order you have placed. you could jump to order history any time by the top right button named “order history”.

accountinfo.php: you could change your personal information here. old password will not show up on this page, but you could change it into a new one. you could jump to order history any time by the top right button named “order history”. 

confirminfo.php: you could also change your personal information here. this is the page showing up when you place a order to help you confirm your private info.

changeqty.php: this is a page, when changing product quantity inside shopping cart, a AJAX  message will be sent to this php. and this php returns the total price of all stuff inside shopping cart after the quantity has been changed.

showO.php: when click order details in orders.php page, you will jump to this page and showing up details about a certain order. 

files under assign2:
I add a new button in manager page, named “Order report”; view all orders here.
orderreport.php: this shows all search options, manager could choose none or any number of search options and find corresponding orders.
showreport.php: shows up detailed order result.

3. guideline for customers:
login: http://cs-server.usc.edu:3399/assign3/login.php . 
signup: click top right button “sign up“ to create a new account.
(you could not search products before login.)
after login:
you could search using the top search button. and see details about specific product by clicking that product.
you could click top right button “Your Account” to change private info, “order history” go to see order hightory, “cart” to see the shopping cart, “logout” to log out.
when seeing details about a specific product, you could click “buy now” to add this product into shopping cart, and you will also see recommended product (extra credit), or you could simply “add it into cart”.
when inside shopping cart, you could “process you order” at any time, and we will ask you to confirm your private info at next page. 

4.guideline for managers:
managers now have the ability to view orders. Click the “order report button” on the main page. manager should choose specific button about orders they are interested in.
WARNNING: the start date and end date option should in the following format: YYYY-MM-DD, and start date should be earlier than end date.


5.extra work:
	Extra credit work has been done, once customer clicks “buy now”, page will jump to the shopping cart. and if there is item that other customers also bought in a single order, it will show up on the right side of screen named “People buy this product also buy”.