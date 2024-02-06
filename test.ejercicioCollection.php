<?php
require_once('class.collection.php');
abstract class AbstractTask {
    private $title;
    private $date;
    private $dueDate;
    private $assignedTo;
    private $description;

    public function setTitle($title){
        $this->title = $title;
    }

    public function getTitle(){
        return $this->title;
    }

    public function setDate($date){
        $this->date = $date;
    }

    public function getDate(){
        return $this->date;
    }

    public function setDueDate($dueDate){
        $this->dueDate = $dueDate;
    }

    public function getDueDate(){
        return $this->dueDate;
    }

    public function setAssignedTo($assignedTo){
        $this->assignedTo = $assignedTo;
    }

    public function getAssignedTo(){
        return $this->assignedTo;
    }

    public function setDescription($description){
        $this->description = $description;
    }

    public function getDescription(){
        echo "- Tarea: " . $this->getTitle();
        echo "<ul>";
        echo "<li>Fecha: " . $this->getDate() . "</li>";
        echo "<li>Fecha entrega: " . $this->getDueDate() . "</li>";
        echo "<li>Asignado: " . $this->getAssignedTo() . "</li>";
        echo "<li>Descripción: " . $this->description . "</li>";
    }
}

class TaskCollection extends Collection {
    public function addTask(AbstractTask $task = null, $key = null) {
        $this->addItem($task, $key);
    }
}

class Project extends AbstractTask {
    private $budget;
    public $Workitems;

    function __construct($title, $date, $dueDate, $assignedTo, $description, $budget){
        parent::setTitle($title);
        parent::setDate($date);
        parent::setDueDate($dueDate);
        parent::setAssignedTo($assignedTo);
        parent::setDescription($description);
        $this->setBudget($budget);
        $this->Workitems = new TaskCollection();
    }

    public function add(AbstractTask $Workitem){
        $this->Workitems->addTask($Workitem);
    }

    public function remove(AbstractTask $Workitem){
        $this->Workitems->removeItem($Workitem);
    }

    public function setBudget($budget){
        $this->budget = $budget;
    }

    public function getBudget(){
        return $this->budget;
    }

    public function hasChildren() {
        return $this->Workitems->length() > 0;
    }

    public function getChild($i) {
        return $this->Workitems->getItem($i);
    }

    public function getDescription(){
        parent::getDescription();
        echo "<li>Presupuesto: " . $this->getBudget() . "</li>";
        if ($this->hasChildren()){
            echo "<ul> Workitems: </br>";
            for ($i = 0; $i < $this->Workitems->length(); $i++) {
                $this->Workitems->getItem($i)->getDescription();
            }
            echo "</ul>";
        } else {
            echo "</ul>";
        }
    }
}

class TimeBasedTask extends AbstractTask {
    private $estimatedHours;
    private $hoursSpent;
    private $ChildTasks;

    public function __construct($title, $date, $dueDate, $assignedTo, $description, $hoursEstimated, $hoursSpent){
        parent::setTitle($title);
        parent::setDate($date);
        parent::setDueDate($dueDate);
        parent::setAssignedTo($assignedTo);
        parent::setDescription($description);
        $this->setEstimatedHours($hoursEstimated);
        $this->setHoursSpent($hoursSpent);
        $this->ChildTasks = new TaskCollection();
    }

    public function add(AbstractTask $childTask){
        $this->ChildTasks->addTask($childTask);
    }

    public function remove(AbstractTask $childTask){
        $this->ChildTasks->removeItem($childTask);
    }

    public function setEstimatedHours($estimatedHours){
        $this->estimatedHours = $estimatedHours;
    }

    public function getEstimatedHours(){
        return $this->estimatedHours;
    }

    public function setHoursSpent($hoursSpent){
        $this->hoursSpent = $hoursSpent;
    }

    public function getHoursSpent(){
        return $this->hoursSpent;
    }

    public function hasChildren() {
        return $this->ChildTasks->length() > 0;
    }

    public function getChild($i) {
        return $this->ChildTasks->getItem($i);
    }

    public function __toString() {
      $result = parent::getDescription(); 

      $result .= "<li>Horas estimadas: " . $this->getEstimatedHours() . "</li>";
      $result .= "<li>Horas totales: " . $this->getHoursSpent() . "</li>";

      if ($this->hasChildren()) {
          $result .= "<ul>";
          for ($i = 0; $i < $this->ChildTasks->length(); $i++) {
              $result .= $this->ChildTasks->getItem($i)->__toString();
          }
          $result .= "</ul>";
      } else {
          $result .= "</ul>";
      }

      return $result;
  }
}

class FixedBudgetTask extends AbstractTask {
    private $budget;
    private $ChildTasks;

    public function __construct($title, $date, $dueDate, $assignedTo, $description, $budget){
        parent::setTitle($title);
        parent::setDate($date);
        parent::setDueDate($dueDate);
        parent::setAssignedTo($assignedTo);
        parent::setDescription($description);
        $this->setBudget($budget);
        $this->ChildTasks = new TaskCollection();
    }

    public function add(AbstractTask $childTask){
        $this->ChildTasks->addTask($childTask);
    }

    public function remove(AbstractTask $childTask){
        $this->ChildTasks->removeItem($childTask);
    }

    public function setBudget($budget){
        $this->budget = $budget;
    }

    public function getBudget(){
        return $this->budget;
    }

    public function hasChildren() {
        return $this->ChildTasks->length() > 0;
    }

    public function getChild($i) {
        return $this->ChildTasks->getItem($i);
    }

    public function getDescription(){
        parent::getDescription();
        echo "<li>Presupuesto: " . $this->getBudget() . "</li>";
        if ($this->hasChildren()){
            echo "<ul>";
            for ($i = 0; $i < $this->ChildTasks->length(); $i++) {
                $this->ChildTasks->getItem($i)->getDescription();
            }
            echo "</ul>";
        } else {
            echo "</ul>";
        }
    }
}

$Workitems = new TaskCollection();
$Workitems->addItem(new TimeBasedTask('Tarea con Tiempo', "2024-02-17", "2024-10-18", "Nico", "Descripción", 10, 5), "0");
echo $Workitems;
$Workitems->addItem(new TimeBasedTask("Tarea con Tiempo2", "2024-01-01", "2024-11-18", "Viri", "Descripción", 11, 6), "1");

echo $Workitems;
