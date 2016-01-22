ALTER TABLE artifact ADD FOREIGN KEY artifactTypeID REFERENCES artifactType(artifactTypeID);
ALTER TABLE artifact ADD FOREIGN KEY artifactLangaugeID REFERENCES language(languageID);

ALTER TABLE category ADD FOREIGN KEY categoryLangaugeID REFERENCES language(languageID);
ALTER TABLE category ADD FOREIGN KEY criterionID REFERENCES criterion(criterionID);

ALTER TABLE cluster_category ADD FOREIGN KEY categoryID REFERENCES category(categoryID);
ALTER TABLE cluster_category ADD FOREIGN KEY clusterID REFERENCES cluster(clusterID);

ALTER TABLE comment ADD FOREIGN KEY userCreated REFERENCES userProfile(userID);

ALTER TABLE criterion ADD FOREIGN KEY criterionLanguageID REFERENCES language(languageID);

ALTER TABLE persona ADD FOREIGN KEY personaLanguageID REFERENCES language(languageID);

ALTER TABLE personaScenario ADD FOREIGN KEY personaID REFERENCES persona(personaID);
ALTER TABLE personaScenario ADD FOREIGN KEY scenarioID REFERENCES scenario(scenarioID);

ALTER TABLE project ADD FOREIGN KEY projectLanguageID REFERENCES language(languageID);

ALTER TABLE projectArtifact ADD FOREIGN KEY artifactID REFERENCES artifact(artifactID);
ALTER TABLE projectArtifact ADD FOREIGN KEY projectID REFERENCES project(projectID);

ALTER TABLE scenario ADD FOREIGN KEY scenarioLanguageID REFERENCES language(languageID);

ALTER TABLE scenarioCategory ADD FOREIGN KEY scenarioID REFERENCES scenario(scenarioID);
ALTER TABLE scenarioCategory ADD FOREIGN KEY categoryID REFERENCES category(categoryID);

ALTER TABLE screenshot ADD FOREIGN KEY userCreated REFERENCES userProfile(userID);

ALTER TABLE userPersonae ADD FOREIGN KEY userID REFERENCES userProfile(userID);
ALTER TABLE userPersonae ADD FOREIGN KEY personaID REFERENCES persona(personaID);

ALTER TABLE userProfile ADD FOREIGN KEY preferredLangauge REFERENCES language(languageID);

ALTER TABLE artifact ADD FOREIGN KEY artifactLangaugeID REFERENCES language(languageID);
ALTER TABLE artifact ADD FOREIGN KEY artifactLangaugeID REFERENCES language(languageID);