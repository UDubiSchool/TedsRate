CREATE TABLE cluster (
    clusterID INT(11) PRIMARY KEY AUTO_INCREMENT,
    clusterName VARCHAR(255) NOT NULL,
    clusterDesc VARCHAR(255)
);

CREATE TABLE cluster_category (
    clusterCategoryID INT(11) PRIMARY KEY AUTO_INCREMENT,
    clusterID INT(11) NOT NULL,
    categoryID INT(11) NOT NULL
);

INSERT INTO cluster (clusterName) VALUES
('Navigation and Findability'),
('Structure'),
('Identity'),
('Parsimony'),
('Completeness'),
('Trustworthiness'),
('Interaction'),
('Customization'),
('Savings'),
('Confidence'),
('Attractiveness'),
('Enjoyment');

INSERT INTO cluster_category (clusterID, categoryID) VALUES
((SELECT attributeID FROM attribute where attributeName = 'Navigation and Findability'), (SELECT attributeID FROM attribute where attributeName = 'Browsing/browsability/searchability')),
((SELECT attributeID FROM attribute where attributeName = 'Navigation and Findability'), (SELECT attributeID FROM attribute where attributeName = 'Mediation')),
((SELECT attributeID FROM attribute where attributeName = 'Navigation and Findability'), (SELECT attributeID FROM attribute where attributeName = 'Orientation')),
((SELECT attributeID FROM attribute where attributeName = 'Navigation and Findability'), (SELECT attributeID FROM attribute where attributeName = 'Simplicity' and criterionID = 1)),

((SELECT attributeID FROM attribute where attributeName = 'Structure'), (SELECT attributeID FROM attribute where attributeName = 'Formatting/Presentation')),
((SELECT attributeID FROM attribute where attributeName = 'Structure'), (SELECT attributeID FROM attribute where attributeName = 'Order/Consistency')),
((SELECT attributeID FROM attribute where attributeName = 'Structure'), (SELECT attributeID FROM attribute where attributeName = 'Accessibility')),


((SELECT attributeID FROM attribute where attributeName = 'Identity'), (SELECT attributeID FROM attribute where attributeName = 'Item Identification')),
((SELECT attributeID FROM attribute where attributeName = 'Identity'), (SELECT attributeID FROM attribute where attributeName = 'Subject description/classification/controlled vocabulary')),
((SELECT attributeID FROM attribute where attributeName = 'Identity'), (SELECT attributeID FROM attribute where attributeName = 'Subject Summary')),
((SELECT attributeID FROM attribute where attributeName = 'Identity'), (SELECT attributeID FROM attribute where attributeName = 'Precision/(relevant retrieved) over (retrieved)')),
((SELECT attributeID FROM attribute where attributeName = 'Identity'), (SELECT attributeID FROM attribute where attributeName = 'Selectivity')),

((SELECT attributeID FROM attribute where attributeName = 'Parsimony'), (SELECT attributeID FROM attribute where attributeName = 'Linkage / Referral')),
((SELECT attributeID FROM attribute where attributeName = 'Parsimony'), (SELECT attributeID FROM attribute where attributeName = 'Order')),
((SELECT attributeID FROM attribute where attributeName = 'Parsimony'), (SELECT attributeID FROM attribute where attributeName = 'Novelty')),

((SELECT attributeID FROM attribute where attributeName = 'Completeness'), (SELECT attributeID FROM attribute where attributeName = 'Accuracy')),
((SELECT attributeID FROM attribute where attributeName = 'Completeness'), (SELECT attributeID FROM attribute where attributeName = 'Comprehensiveness')),
((SELECT attributeID FROM attribute where attributeName = 'Completeness'), (SELECT attributeID FROM attribute where attributeName = 'Currency')),

((SELECT attributeID FROM attribute where attributeName = 'Trustworthiness'), (SELECT attributeID FROM attribute where attributeName = 'Reliability')),
((SELECT attributeID FROM attribute where attributeName = 'Trustworthiness'), (SELECT attributeID FROM attribute where attributeName = 'Validity')),
((SELECT attributeID FROM attribute where attributeName = 'Trustworthiness'), (SELECT attributeID FROM attribute where attributeName = 'Authority')),

((SELECT attributeID FROM attribute where attributeName = 'Interaction'), (SELECT attributeID FROM attribute where attributeName = 'Contextuality/closeness to problem')),
((SELECT attributeID FROM attribute where attributeName = 'Interaction'), (SELECT attributeID FROM attribute where attributeName = 'Transaction')),
((SELECT attributeID FROM attribute where attributeName = 'Interaction'), (SELECT attributeID FROM attribute where attributeName = 'Feedback')),
((SELECT attributeID FROM attribute where attributeName = 'Interaction'), (SELECT attributeID FROM attribute where attributeName = 'Community/social networking')),

((SELECT attributeID FROM attribute where attributeName = 'Customization'), (SELECT attributeID FROM attribute where attributeName = 'Flexibility')),
((SELECT attributeID FROM attribute where attributeName = 'Customization'), (SELECT attributeID FROM attribute where attributeName = 'Simplicity'  and criterionID = 2)),
((SELECT attributeID FROM attribute where attributeName = 'Customization'), (SELECT attributeID FROM attribute where attributeName = 'Trust')),
((SELECT attributeID FROM attribute where attributeName = 'Customization'), (SELECT attributeID FROM attribute where attributeName = 'Individualization')),
((SELECT attributeID FROM attribute where attributeName = 'Customization'), (SELECT attributeID FROM attribute where attributeName = 'Localization')),
((SELECT attributeID FROM attribute where attributeName = 'Customization'), (SELECT attributeID FROM attribute where attributeName = 'Privacy')),

((SELECT attributeID FROM attribute where attributeName = 'Savings'), (SELECT attributeID FROM attribute where attributeName = 'Cost savings')),
((SELECT attributeID FROM attribute where attributeName = 'Savings'), (SELECT attributeID FROM attribute where attributeName = 'Time savings')),

((SELECT attributeID FROM attribute where attributeName = 'Confidence'), (SELECT attributeID FROM attribute where attributeName = 'Security')),
((SELECT attributeID FROM attribute where attributeName = 'Confidence'), (SELECT attributeID FROM attribute where attributeName = 'Safety')),

((SELECT attributeID FROM attribute where attributeName = 'Attractiveness'), (SELECT attributeID FROM attribute where attributeName = 'Aesthetics')),
((SELECT attributeID FROM attribute where attributeName = 'Attractiveness'), (SELECT attributeID FROM attribute where attributeName = 'Satisfaction/rewarding/incenting')),
((SELECT attributeID FROM attribute where attributeName = 'Enjoyment'), (SELECT attributeID FROM attribute where attributeName = 'Entertainment')),
((SELECT attributeID FROM attribute where attributeName = 'Enjoyment'), (SELECT attributeID FROM attribute where attributeName = 'Engagement')),
((SELECT attributeID FROM attribute where attributeName = 'Enjoyment'), (SELECT attributeID FROM attribute where attributeName = 'Stimulation'));
