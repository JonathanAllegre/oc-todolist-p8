Feature: HomePage

  Scenario: test
    Given I am on homepage
    When I follow "Créer un utilisateur"
    Then I should see "Tapez le mot de passe à nouveau"

  Scenario:  Homepage
    Given I am on homepage
    Then I should see "Nom d'utilisateur"

  Scenario: Login
    Given I am on homepage
    When I fill in "_username" with "jonathan"
    And I fill in "password" with "test"
    And I press "Se connecter"
    Then I should see "Bienvenue sur Todo List"
    And I should see "Créer une nouvelle tâche"
    And I should see "Consulter la liste des tâches à faire"

  Scenario: Click on Se déconnecter button
    Given I am on homepage
    When I fill in "username" with "jonathan"
    And I fill in "password" with "test"
    And I press "Se connecter"
    Then I should see "Se déconnecter"
    When I follow "Se déconnecter"
    Then I should see "Nom d'utilisateur"

  Scenario: Click on Créer une nouvelle tâche
    Given I am on homepage
    When I fill in "username" with "jonathan"
    And I fill in "password" with "test"
    And I press "Se connecter"
    When I follow "Créer une nouvelle tâche"
    Then I should see "Title"
    And I should see "Content"
    And I should see "Retour à la liste des tâches"
    And I should see "Ajouter"
    And I should see "Se Déconnecter"
    
  Scenario: Click on Consulter la liste des taches
    Given I am on the homepage
    When I fill in "username" with "jonathan"
    And I fill in "password" with "test"
    And I press "Se connecter"
    When I follow "Consulter la liste des tâches à faire"
#    Then I should see "testBehat"
    And I should see "Le Contenu de ma tache de test"
    

