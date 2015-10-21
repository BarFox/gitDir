Readme file for HW2

the HW2 url is http://cs-server.usc.edu:3399/assign2/login.php , I try to make the page seem like an IOS version.
and you can view the mysql structure here: http://cs-server.usc.edu:3399/phpMyAdmin-4.0.10.10-english/index.php
username:root	password:1992 

(employee account)
username:cty	password:chentian

(admin account)
username:wyy	password:wangya

(manager account)
username:zwy	password:zhangwen

1. mysql structure 
	for assignment2, there are 5 tables.
	employees: userindex, employeeid, employeefname, employeelname, age, salary.
	product: predicted, productcategoryid, productname, productdesc, productprice.
	product category: productcategoryid, productcategoryname, productcategorydesc.
	specialsales:specialsalesid, productid, startdate, enddate.
	users:userindex,username, password, usertype.

2. guideline for users:
	you can’t enter any page without the right username, password and user type. illegal operation will lead users to be sent to login page.
	every user has a logout button at the bottom of page.
	ATTENTION:if you want to input “‘”, please use “‘’”, this is the requirement of mysql. Well, off course I could fix this, but..I really need to prepare for midterm..
	ATTENTION: never delete category before you have delete all products under this category! and the same for product and special sales. This will cause problem.
	ATTENTION: Please follow the requirement of formats as follow, or a error message will show up on page.
	1)employees: 
		(1)add/change/delete product
		for adding product, you need to choose a category in the select list. the content of this list is changing according to the productcategory table.then fill up all other input part. price should be a number. no empty input is allowed. then click “add product”.
		for changing/deleting product, just choose one in the list, and change/delete.

		(2)add/change/delete category
		fill up all input part, no empty input is allowed. then click “add category”.
		for changing/deleting category, just choose one in the list, and change/delete.
		
		(3)add/change/delete special sales
		for adding special sales, you need to choose a category in the select list. the content of this list is changing according to the product table. if you use Google chrome, you could just choose a day in the start date and end date part, if you use other browser, you need to input a day as following ”YYYY-MM-DD”. end date should be later than or equal to start date. a tip about this format will show up on the page.
		the price of special sales is 70% of the original one. the discounted price will show up in the view of manager.
	
	2)admin:
		add/change/delete users
		admin could add all kinds of users into mysql. no empty input is allowed. age should be a number int without “.”. salary
 is a number bigger than 0.
	
	3)manager:
		manager could view al products, employees and special sales, but they could not change anything. they have several options to choose, they could choose none, any or all of them, click “show **”, corresponding result will show up.
		for product name and category part, the content of this list is changing according to the table.
		




3. extra work:
>using unix_timestamp function to store start and end date, which only uses 10 int. I believe this way will save space in mysql.
>a logout button for every user.
>when adding product, you should choose a category inside select list instead of input a one, this will prevent employee from making mistake. the same for special sales and product.


