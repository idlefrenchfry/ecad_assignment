--  Module: ECAD                                        
--  Database Script for setting up the MySQL database   
--  required for ECAD Assignment.             
--  Creation Date: 10 Dec 2020. 

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- Delete tables before creating   

DROP TABLE IF EXISTS GST;
DROP TABLE IF EXISTS OrderData;
DROP TABLE IF EXISTS ShopCartItem;
DROP TABLE IF EXISTS ShopCart;
DROP TABLE IF EXISTS CatProduct;
DROP TABLE IF EXISTS ProductSpec;
DROP TABLE IF EXISTS Specification;
DROP TABLE IF EXISTS Product;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Feedback;
DROP TABLE IF EXISTS Shopper;


-- Create the tables 

CREATE TABLE Shopper 
(
  ShopperID INT(4) NOT NULL AUTO_INCREMENT,
  Name VARCHAR(50) NOT NULL,
  BirthDate DATE DEFAULT NULL,
  Address VARCHAR(150) DEFAULT NULL,
  Country VARCHAR(50) DEFAULT NULL,
  Phone VARCHAR(20) DEFAULT NULL,
  Email VARCHAR(50) NOT NULL,
  Password VARCHAR(20) NOT NULL,
  PwdQuestion VARCHAR(100) DEFAULT NULL,
  PwdAnswer VARCHAR(50) DEFAULT NULL,
  ActiveStatus INT(4) NOT NULL DEFAULT 1,
  DateEntered TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (ShopperID)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE Feedback
(
  FeedBackID INT(4) NOT NULL AUTO_INCREMENT,
  ShopperID INT(4) NOT NULL,
  Subject VARCHAR(255) NULL,
  Content LONGTEXT NULL,
  Rank INT(4) DEFAULT NULL,
  DateTimeCreated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (FeedBackID),
  FOREIGN KEY fk_Feedback_Shopper(ShopperID) REFERENCES Shopper(ShopperID)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE Category 
(
  CategoryID INT(4) NOT NULL AUTO_INCREMENT,
  CatName VARCHAR(255) DEFAULT NULL,
  CatDesc LONGTEXT DEFAULT NULL,
  CatImage VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (CategoryID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE Product 
(
  ProductID INT(4) NOT NULL AUTO_INCREMENT,
  ProductTitle VARCHAR(255) DEFAULT NULL,
  ProductDesc LONGTEXT DEFAULT NULL,
  ProductImage VARCHAR(255) DEFAULT NULL,
  Price DOUBLE NOT NULL DEFAULT 0.0,
  Quantity INT(4) NOT NULL DEFAULT 0,
  Offered INT(4) NOT NULL DEFAULT 0,
  OfferedPrice DOUBLE DEFAULT NULL,
  OfferStartDate DATE DEFAULT NULL,
  OfferEndDate DATE DEFAULT NULL,
  PRIMARY KEY (ProductID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE Specification
(
  SpecID INT(4) NOT NULL AUTO_INCREMENT,
  SpecName VARCHAR(64) DEFAULT NULL,
  PRIMARY KEY (SpecID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE ProductSpec 
(
  ProductID INT(4) NOT NULL,
  SpecID INT(4) NOT NULL,
  SpecVal VARCHAR(255) DEFAULT NULL,
  Priority INT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (ProductID, SpecID),
  FOREIGN KEY fk_PS_Product(ProductID) REFERENCES Product(ProductID),
  FOREIGN KEY fk_PS_Specification(SpecID) REFERENCES Specification(SpecID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE CatProduct 
(
  CategoryID INT(4) NOT NULL,
  ProductID INT(4) NOT NULL,
  PRIMARY KEY (CategoryID, ProductID),
  FOREIGN KEY fk_CP_Category(CategoryID) REFERENCES Category(CategoryID),
  FOREIGN KEY fk_CP_Product(ProductID) REFERENCES Product(ProductID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE ShopCart
(
  ShopCartID INT(4) NOT NULL AUTO_INCREMENT,
  ShopperID INT(4) NOT NULL,
  OrderPlaced INT(4) NOT NULL DEFAULT 0,
  Quantity INT(4) DEFAULT NULL,  
  SubTotal DOUBLE DEFAULT NULL,
  Tax DOUBLE DEFAULT NULL,
  ShipCharge DOUBLE DEFAULT NULL,
  Discount DOUBLE NOT NULL DEFAULT 0.0,
  Total DOUBLE DEFAULT NULL,
  DateCreated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (ShopCartID),
  FOREIGN KEY fk_SC_Shopper(ShopperID) REFERENCES Shopper(ShopperID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE ShopCartItem 
(
  ShopCartID INT(4) NOT NULL,
  ProductID INT(4) NOT NULL,
  Price DOUBLE NOT NULL,
  Name VARCHAR(255) NOT NULL,
  Quantity INT(4) NOT NULL,
  PRIMARY KEY (ShopCartID, ProductID),
  FOREIGN KEY fk_SCI_ShopCart(ShopCartID) REFERENCES ShopCart(ShopCartID),
  FOREIGN KEY fk_SCI_Product(ProductID) REFERENCES Product(ProductID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE OrderData
(
  OrderID INT(4) NOT NULL AUTO_INCREMENT,
  ShopCartID INT(4) NOT NULL,
  ShipName VARCHAR(50) NOT NULL,
  ShipAddress VARCHAR(150) NOT NULL,
  ShipCountry VARCHAR(50) NOT NULL,
  ShipPhone VARCHAR(20) DEFAULT NULL,
  ShipEmail VARCHAR(50) DEFAULT NULL,
  BillName VARCHAR(50) NOT NULL,
  BillAddress VARCHAR(150) NOT NULL,
  BillCountry VARCHAR(50) NOT NULL,
  BillPhone VARCHAR(20) DEFAULT NULL,
  BillEmail VARCHAR(50) DEFAULT NULL,
  DeliveryDate DATE DEFAULT NULL,
  DeliveryTime VARCHAR(50) DEFAULT NULL,
  DeliveryMode VARCHAR(50) DEFAULT NULL,
  Message VARCHAR(255) DEFAULT NULL,
  OrderStatus INT(4) NOT NULL DEFAULT 1,
  DateOrdered TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,  
  PRIMARY KEY (OrderID),
  FOREIGN KEY fk_Order_ShopCart(ShopCartID) REFERENCES ShopCart(ShopCartID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE GST 
(
  GstId INT(4) NOT NULL AUTO_INCREMENT,
  EffectiveDate DATE NOT NULL,
  TaxRate DOUBLE NOT NULL,
  PRIMARY KEY (GstId)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- Load the tables with sample data  


insert into Shopper(Name, BirthDate, Address, Country, Phone, EMail, Password, PwdQuestion, PwdAnswer, ActiveStatus, DateEntered) 
values('James Ecader','1970-01-01','School of Infocomm Technology, Ngee Ann Polytechnic','Singapore','(65) 64601234','ecader@np.edu.sg','ecader','Which polytechnic?','Ngee Ann', 1, '2013-01-01 10:05:30' );

insert into Shopper(Name, BirthDate, Address, Country, Phone, EMail, Password, PwdQuestion, PwdAnswer, ActiveStatus, DateEntered) 
values('Peter Tan','1977-05-15','Blk 108, Hougang Ave 1, #04-04','Singapore','(65) 62881111','PeterTan@hotmail.com','PeterTan','wife''s name?','Lucy', 0, '2013-01-01 15:35:20' );

insert into Shopper(Name, BirthDate, Address, Country, Phone, EMail, Password, PwdQuestion, PwdAnswer, ActiveStatus, DateEntered) 
values('Mary Mai','1982-08-09','123, Sunset Way, Spore 555123','Singapore','(65) 62881111','MaryMai@yahoo.com','MaryMai','How many brothers and sisters?','0', 1, '2012-05-01 09:45:23' );


insert into Category(CatName, CatDesc, CatImage) 
values('Flowers','The colourful crafted hand bouquets with absolute attention to details are guaranteed to brighten ones day.','Flowers.jpg');

insert into Category(CatName, CatDesc, CatImage) 
values('Gifts','Nothing says you are special more than our wide range of gifts that is bound to bring joy to your love ones.','Gifts.jpg');

insert into Category(CatName, CatDesc, CatImage) 
values('Hampers','Brighten someone\'s day from our delectable collection of sweet and savory treats all wrapped up in a fancy basket to wow your recipients.','Hampers.jpg');


insert into Specification(SpecName) values('Occasion');
insert into Specification(SpecName) values('Size');
insert into Specification(SpecName) values('Weight');


insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity) 
values('Pink Lady', 'Wrapped in pretty dolly pink paper, let our splendid ensemble of 12 Pink Roses and Pink Alstroemerias beautify one\'s day.',
'Pink_Lady.jpg', 60.00, 200);

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity, Offered, OfferedPrice, OfferStartDate, OfferEndDate) 
values('Blooms of Sunshine', 'Vibrant and brilliant, this will brighten anyone\'s day with its cheerful arrangement of Sunflowers, Anthuriums and Hypericums.',
'Blooms_of_Sunshine.jpg', 90.00, 300, 1, 72.00, '2020-12-01', '2020-12-31');

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity, Offered, OfferedPrice, OfferStartDate, OfferEndDate) 
values('Lavish Prosperity', 'Congratulatory Flower Stand. *Includes Handmade Flowers',
'Lavish_Prosperity.jpg', 60.00, 300, 1, 45.00, '2021-01-01', '2021-03-31');

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity) 
values('Springtime Bloom', 'Nothing is more adorable than a 28cm baby chick plush nestled in a vibrant bed of Sunflowers and mini Roses.',
'Springtime_Bloom.jpg', 70.00, 100);

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity, Offered, OfferedPrice, OfferStartDate, OfferEndDate) 
values('Together Forever', 'Good things come in pairs. Wish a newlywed eternal love with a pair of couple face towels and two pairs of slippers to accompany them down their lifelong journey.',
'Together_Forever.jpg', 65.00, 100, 1, 50.50, '2021-01-01', '2021-01-31');

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity, Offered, OfferedPrice, OfferStartDate, OfferEndDate) 
values('Deluxe Diaper Cake (Girl)', 'Welcome the arrival of the little one with: 22cm Cute Little Bunny, 10pcs Pink Shears Baby Wear Gift Set, Soft Towel with Cap and 21pkts Drypers Diapers.',
'Deluxe_Diaper_Cake_Girl.jpg', 160.00, 5, 1, 99.00, '2021-01-01', '2021-03-01');

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity, Offered, OfferedPrice, OfferStartDate, OfferEndDate) 
values('Blossoming Health', 'Send a sweet and thoughtful gesture to encourage one to have a speedy recovery. Hamper includes: Assorted Stuffed Toy, Keebler Chips Deluxe Cookies, Yum Earth Organics Assorted Lollipops, Lotte Koala\'s March Chocolate Snack with Strawberry Cream Filling, Kellogg\'s Froot Loops Cereal, Nestle Milo Drink 2x200ml.',
'Blossoming_Health.jpg', 60.00, 100, 1, 29.90, '2021-03-01', '2021-03-31');

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity, Offered, OfferedPrice, OfferStartDate, OfferEndDate) 
values('Vine Grace', 'Toast to happiness with Chateau Haut-Domingue Bordeaux Red Wine 75cl, Ferrero Rocher Chocolate 300g and Lindt Swiss Classic White Chocolate with Almond Brittle 100g, packaged with yellow Roses and White Cymbidiums.',
'Vine_Grace.jpg', 100.00, 2, 1, 70.00, '2021-01-01', '2021-03-31');

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity) 
values('Blissful Bundle', 'Delicious fruits embellished with fresh Orchids packaged in White Handwoven Handle Basket.',
'Blissful_Bundle.jpg', 60.00, 0);


insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(1, 1, 'Romance', 1);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(2, 2, '78cm x 55cm', 1);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(2, 1, 'Thank You', 2);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(3, 2, '165cm x 80cm', 1);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(3, 1, 'Official Opening', 2);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(4, 1, 'Birthday', 1);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(5, 1, 'Wedding', 1);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(6, 1, 'Newborn', 1);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(7, 1, 'Get Well', 2);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(7, 3, '1760g', 1);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(8, 1, 'Thank You', 2);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(8, 3, '4500g', 1);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(9, 1, 'Get Well', 2);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(9, 3, '1340g', 1);


insert into CatProduct(CategoryID, ProductID) values(1,1);
insert into CatProduct(CategoryID, ProductID) values(1,2);
insert into CatProduct(CategoryID, ProductID) values(1,3);
insert into CatProduct(CategoryID, ProductID) values(2,4);
insert into CatProduct(CategoryID, ProductID) values(2,5);
insert into CatProduct(CategoryID, ProductID) values(2,6);
insert into CatProduct(CategoryID, ProductID) values(3,7);
insert into CatProduct(CategoryID, ProductID) values(3,8);
insert into CatProduct(CategoryID, ProductID) values(3,9);


insert into ShopCart (ShopperId, OrderPlaced, Quantity, Subtotal, Tax, ShipCharge, Discount, Total, DateCreated)
values(1, 1, 3, 212.00, 14.84, 5.00, 0.00, 231.84,'2016-12-22 09:56:30');


insert into ShopCartItem(ShopCartId, ProductId, Name, Price, Quantity) 
values(1, 2, 'Blooms of Sunshine', 72.00, 1);
insert into ShopCartItem(ShopCartId, ProductId, Name, Price, Quantity) 
values(1, 4, 'Springtime Bloom', 70.00, 2);


insert into OrderData(ShopCartId,ShipName,ShipAddress,ShipCountry,ShipPhone,ShipEmail,
BillName,BillAddress,BillCountry,BillPhone,BillEmail,DeliveryDate,DeliveryMode,
Message,OrderStatus,DateOrdered) 
values(1, 'Jenny Lai', 'Blk 222, Ang Mo Kio Ave 1, #12-12, S(560222)', 'Singapore', '(65) 63447777', 'JennyLai@yahoo.com.sg', 
'James Ecader', 'School of InfoComm Technology, Ngee Ann Polytechnic', 'Singapore','(65) 64601234', 'ecader@np.edu.sg', '2016-12-23', 'Normal',
'Merry Christmas!', 3, '2016-12-22 10:01:35');


insert into Feedback(ShopperID, Subject, Content, Rank, DateTimeCreated)
values(1, 'Feebdack about the service', 'The website provides helpful information. Fast in delivery goods.', 4, '2015-12-23 09:50:30');


insert into GST(EffectiveDate, TaxRate) values ('2004-01-01',5.0);
insert into GST(EffectiveDate, TaxRate) values ('2007-07-01',7.0);
insert into GST(EffectiveDate, TaxRate) values ('2020-01-01',8.0);
