

LOAD DATA INFILE 'Reservations.dat' INTO TABLE Reservations
     FIELDS TERMINATED BY ','
     LINES TERMINATED BY '\n'
     (roomNum, reservationID, custID, bill, checkInDate, checkOutDate);

LOAD DATA INFILE 'Room.dat' INTO TABLE Room
     FIELDS TERMINATED BY ','
     LINES TERMINATED BY '\n'
     (roomNum, floor, availability, inclusivePackageNum, numBeds, roomTier, roomView);

LOAD DATA INFILE 'RoomTier.dat' INTO TABLE RoomTier
    FIELDS TERMINATED BY ','
    LINES TERMINATED BY '\n'
    (roomTier, rate);

LOAD DATA INFILE 'Customer.dat' INTO TABLE Customer
     FIELDS TERMINATED BY ','
     LINES TERMINATED BY '\n'
     (custID, cFName, cLName, cEmail, cPhoneNum, cardInfo, billingAddress, mmbrTier, mmbrPoints, mmbrStatus);

LOAD DATA 'Employee.dat' INTO TABLE Employee
     FIELDS TERMINATED BY ','
     LINES TERMINATED BY '\n'
     (empID, eFName, eLName, eDept, jobTitle, joinDate);

LOAD DATA 'Essn.dat' INTO TABLE Essn
     FIELDS TERMINATED BY ','
     LINES TERMINATED BY '\n'
     (empSSN, empID);

LOAD DATA 'JobTitle.dat' INTO TABLE JobTitle
     FIELDS TERMINATED BY ','
     LINES TERMINATED BY '\n'
     (jobTitle, payRate);

LOAD DATA 'Hotel.dat' INTO TABLE Hotel
     FIELDS TERMINATED BY ','
     LINES TERMINATED BY '\n'
     (hno, hlocation, managerSSN, ownerSSN, numRooms, numEmps);