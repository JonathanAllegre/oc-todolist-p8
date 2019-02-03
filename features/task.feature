Feature: TaskPage

  Scenario: homeTask
    Given I am on homepage
    When I fill in "_username" with "jonathan"
    And I fill in "password" with "test"
    And I press "Se connecter"
    Given I am on "/tasks"
    Then I should see "Une Tache de test"
    Then I should see "Le Contenu de ma tache de test"

  Scenario: CreateTask
    Given I am on homepage
    When I fill in "_username" with "jonathan"
    And I fill in "password" with "test"
    And I press "Se connecter"
    Given I am on "/tasks/create"
    Then I should see "Title"
    Then I should see "Content"
    When I fill in "task_title" with "task test behat"
    And I fill in "task_content" with "task behat content test"
    And I press "Ajouter"
    Then I should see "Superbe ! La tâche a été bien été ajoutée."
    And I should see "task test behat"

  Scenario: EditTask
    Given I am on homepage
    When I fill in "_username" with "jonathan"
    And I fill in "password" with "test"
    And I press "Se connecter"
    Given I am on "/tasks/1/edit"
    When I fill in "task_title" with "task test behat modifié"
    And I fill in "task_content" with "task behat content test modifié"
    And I press "Modifier"
    Then I should see "Superbe ! La tâche a bien été modifiée."
    And I should see "task test behat modifié"

  Scenario: ToggleTask
    Given I am on homepage
    When I fill in "_username" with "jonathan"
    And I fill in "password" with "test"
    And I press "Se connecter"
    Given I am on "/tasks"
    When I press "task-1"
    Then I should see "Superbe ! La tâche task test behat modifié a bien été marquée comme faite."
