CREATE TABLE configurationType (
    configurationTypeID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    configurationTypeName VARCHAR(255) NOT NULL,
    configurationTypeDesc VARCHAR(500)
);

CREATE TABLE configuration (
    configurationID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    configurationName VARCHAR(255) NOT NULL,
    configurationDesc VARCHAR(500),
    configurationTypeID INT(11) NOT NULL,
    dateCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (configurationTypeID) REFERENCES configurationType(configurationTypeID)
);

CREATE TABLE configuration_cluster (
    configurationClusterID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    configurationID INT(11) NOT NULL,
    clusterID INT(11) NOT NULL,
    FOREIGN KEY (configurationID) REFERENCES configuration(configurationID),
    FOREIGN KEY (clusterID) REFERENCES cluster(clusterID)
);

CREATE TABLE configuration_category (
    configurationAttributeID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    configurationID INT(11) NOT NULL,
    categoryID INT(11) NOT NULL,
    FOREIGN KEY (configurationID) REFERENCES configuration(configurationID),
    FOREIGN KEY (categoryID) REFERENCES category(categoryID)
);

INSERT INTO configurationType (configurationTypeName, configurationTypeDesc) VALUES
('Attribute', 'A configuration of attributes.'),
('Cluster', 'A configuration of clusters.');

INSERT INTO configuration (configurationName, configurationDesc, configurationTypeID) VALUES
('All Original Attributes', 'A configuration of all 40 original attributes.', (SELECT configurationTypeID FROM configurationType WHERE configurationTypeName = 'Attribute')),
('All Original Clusters', 'A configuration of all 12 original clusters.', (SELECT configurationTypeID FROM configurationType WHERE configurationTypeName = 'Cluster'));

INSERT INTO configuration_category(configurationID, categoryID) VALUES
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Browsing/browsability/searchability')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Formatting/Presentation')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Mediation')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Orientation')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Order/Consistency')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Accessibility')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Simplicity' AND criterionID = 1)),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Item Identification')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Subject description/classification/controlled vocabulary')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Subject Summary')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Linkage / Referral')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Precision/(relevant retrieved) over (retrieved)')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Selectivity')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Order')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Novelty')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Accuracy')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Comprehensiveness')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Currency')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Reliability')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Validity')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Authority')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Contextuality/closeness to problem')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Flexibility')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Simplicity' AND criterionID = 2)),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Transaction')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Trust')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Feedback')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Community/social networking')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Individualization')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Localization')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Privacy')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Cost savings')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Time savings')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Security')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Safety')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Aesthetics')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Entertainment')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Engagement')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Stimulation')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes'), (SELECT categoryID FROM category WHERE categoryName = 'Satisfaction/rewarding/incenting'));

INSERT INTO configuration_cluster (configurationID, clusterID) VALUES
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Clusters'), (SELECT clusterID FROM cluster WHERE clusterName = 'Navigation and Findability')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Clusters'), (SELECT clusterID FROM cluster WHERE clusterName = 'Structure')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Clusters'), (SELECT clusterID FROM cluster WHERE clusterName = 'Identity')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Clusters'), (SELECT clusterID FROM cluster WHERE clusterName = 'Parsimony')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Clusters'), (SELECT clusterID FROM cluster WHERE clusterName = 'Completeness')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Clusters'), (SELECT clusterID FROM cluster WHERE clusterName = 'Trustworthiness')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Clusters'), (SELECT clusterID FROM cluster WHERE clusterName = 'Interaction')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Clusters'), (SELECT clusterID FROM cluster WHERE clusterName = 'Customization')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Clusters'), (SELECT clusterID FROM cluster WHERE clusterName = 'Savings')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Clusters'), (SELECT clusterID FROM cluster WHERE clusterName = 'Confidence')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Clusters'), (SELECT clusterID FROM cluster WHERE clusterName = 'Attractiveness')),
((SELECT configurationID FROM configuration WHERE configurationName = 'All Original Clusters'), (SELECT clusterID FROM cluster WHERE clusterName = 'Enjoyment'));

ALTER TABLE assessment ADD COLUMN configurationID INT(11) AFTER assessmentIDHashed;

ALTER TABLE assessment ADD FOREIGN KEY (configurationID) REFERENCES configuration(configurationID);

UPDATE assessment SET configurationID = (SELECT configurationID FROM configuration WHERE configurationName = 'All Original Attributes');