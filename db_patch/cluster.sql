CREATE TABLE cluster (
    clusterID INT(11) PRIMARY KEY AUTO_INCREMENT,
    clusterName VARCHAR(255) NOT NULL,
    clusterDesc VARCHAR(255)
);

CREATE TABLE clusterCategory (
    clusterCategoryID INT(11) PRIMARY KEY AUTO_INCREMENT,
    clusterID INT(11) NOT NULL,
    categoryID INT(11) NOT NULL
);