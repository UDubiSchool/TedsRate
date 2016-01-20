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
((SELECT clusterID FROM cluster where clusterName = 'Navigation and Findability'), (SELECT categoryID FROM category where categoryTitle = 'Browsing/browsability/searchability')),
((SELECT clusterID FROM cluster where clusterName = 'Navigation and Findability'), (SELECT categoryID FROM category where categoryTitle = 'Mediation')),
((SELECT clusterID FROM cluster where clusterName = 'Navigation and Findability'), (SELECT categoryID FROM category where categoryTitle = 'Orientation')),
((SELECT clusterID FROM cluster where clusterName = 'Navigation and Findability'), (SELECT categoryID FROM category where categoryTitle = 'Simplicity' and criteriaID = 1)),

((SELECT clusterID FROM cluster where clusterName = 'Structure'), (SELECT categoryID FROM category where categoryTitle = 'Formatting/Presentation')),
((SELECT clusterID FROM cluster where clusterName = 'Structure'), (SELECT categoryID FROM category where categoryTitle = 'Order/Consistency')),
((SELECT clusterID FROM cluster where clusterName = 'Structure'), (SELECT categoryID FROM category where categoryTitle = 'Accessibility')),


((SELECT clusterID FROM cluster where clusterName = 'Identity'), (SELECT categoryID FROM category where categoryTitle = 'Item Identification')),
((SELECT clusterID FROM cluster where clusterName = 'Identity'), (SELECT categoryID FROM category where categoryTitle = 'Subject description/classification/controlled vocabulary')),
((SELECT clusterID FROM cluster where clusterName = 'Identity'), (SELECT categoryID FROM category where categoryTitle = 'Subject Summary')),
((SELECT clusterID FROM cluster where clusterName = 'Identity'), (SELECT categoryID FROM category where categoryTitle = 'Precision/(relevant retrieved) over (retrieved)')),
((SELECT clusterID FROM cluster where clusterName = 'Identity'), (SELECT categoryID FROM category where categoryTitle = 'Selectivity')),

((SELECT clusterID FROM cluster where clusterName = 'Parsimony'), (SELECT categoryID FROM category where categoryTitle = 'Linkage / Referral')),
((SELECT clusterID FROM cluster where clusterName = 'Parsimony'), (SELECT categoryID FROM category where categoryTitle = 'Order')),
((SELECT clusterID FROM cluster where clusterName = 'Parsimony'), (SELECT categoryID FROM category where categoryTitle = 'Novelty')),

((SELECT clusterID FROM cluster where clusterName = 'Completeness'), (SELECT categoryID FROM category where categoryTitle = 'Accuracy')),
((SELECT clusterID FROM cluster where clusterName = 'Completeness'), (SELECT categoryID FROM category where categoryTitle = 'Comprehensiveness')),
((SELECT clusterID FROM cluster where clusterName = 'Completeness'), (SELECT categoryID FROM category where categoryTitle = 'Currency')),

((SELECT clusterID FROM cluster where clusterName = 'Trustworthiness'), (SELECT categoryID FROM category where categoryTitle = 'Reliability')),
((SELECT clusterID FROM cluster where clusterName = 'Trustworthiness'), (SELECT categoryID FROM category where categoryTitle = 'Validity')),
((SELECT clusterID FROM cluster where clusterName = 'Trustworthiness'), (SELECT categoryID FROM category where categoryTitle = 'Authority')),

((SELECT clusterID FROM cluster where clusterName = 'Interaction'), (SELECT categoryID FROM category where categoryTitle = 'Contextuality/closeness to problem')),
((SELECT clusterID FROM cluster where clusterName = 'Interaction'), (SELECT categoryID FROM category where categoryTitle = 'Transaction')),
((SELECT clusterID FROM cluster where clusterName = 'Interaction'), (SELECT categoryID FROM category where categoryTitle = 'Feedback')),
((SELECT clusterID FROM cluster where clusterName = 'Interaction'), (SELECT categoryID FROM category where categoryTitle = 'Community/social networking')),

((SELECT clusterID FROM cluster where clusterName = 'Customization'), (SELECT categoryID FROM category where categoryTitle = 'Flexibility')),
((SELECT clusterID FROM cluster where clusterName = 'Customization'), (SELECT categoryID FROM category where categoryTitle = 'Simplicity'  and criteriaID = 2)),
((SELECT clusterID FROM cluster where clusterName = 'Customization'), (SELECT categoryID FROM category where categoryTitle = 'Trust')),
((SELECT clusterID FROM cluster where clusterName = 'Customization'), (SELECT categoryID FROM category where categoryTitle = 'Individualization')),
((SELECT clusterID FROM cluster where clusterName = 'Customization'), (SELECT categoryID FROM category where categoryTitle = 'Localization')),
((SELECT clusterID FROM cluster where clusterName = 'Customization'), (SELECT categoryID FROM category where categoryTitle = 'Privacy')),

((SELECT clusterID FROM cluster where clusterName = 'Savings'), (SELECT categoryID FROM category where categoryTitle = 'Cost savings')),
((SELECT clusterID FROM cluster where clusterName = 'Savings'), (SELECT categoryID FROM category where categoryTitle = 'Time savings')),

((SELECT clusterID FROM cluster where clusterName = 'Confidence'), (SELECT categoryID FROM category where categoryTitle = 'Security')),
((SELECT clusterID FROM cluster where clusterName = 'Confidence'), (SELECT categoryID FROM category where categoryTitle = 'Safety')),

((SELECT clusterID FROM cluster where clusterName = 'Attractiveness'), (SELECT categoryID FROM category where categoryTitle = 'Aesthetics')),
((SELECT clusterID FROM cluster where clusterName = 'Attractiveness'), (SELECT categoryID FROM category where categoryTitle = 'Satisfaction/rewarding/incenting')),
((SELECT clusterID FROM cluster where clusterName = 'Enjoyment'), (SELECT categoryID FROM category where categoryTitle = 'Entertainment')),
((SELECT clusterID FROM cluster where clusterName = 'Enjoyment'), (SELECT categoryID FROM category where categoryTitle = 'Engagement')),
((SELECT clusterID FROM cluster where clusterName = 'Enjoyment'), (SELECT categoryID FROM category where categoryTitle = 'Stimulation'));
