<?php

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

class Project extends AbstractTask {
    private $budget;
    private $Workitems = array();

    function __construct($title, $date, $dueDate, $assignedTo, $description, $budget){
        parent::setTitle($title);
        parent::setDate($date);
        parent::setDueDate($dueDate);
        parent::setAssignedTo($assignedTo);
        parent::setDescription($description);
        $this->setBudget($budget);
    }
    

    public function add(AbstractTask $Workitem){
        array_push($this->Workitems, $Workitem);
    }

    public function remove(AbstractTask $Workitem){
        array_pop($this->Workitems);
    }

    public function setBudget($budget){
        $this->budget = $budget;
    }

    public function getBudget(){
        return $this->budget;
    }

    public function hasChildren() {
        return (bool)(count($this->Workitems) > 0);
    }

    public function getChild($i) {
        return $this->Workitems[$i];
    }

    public function getDescription(){
        parent::getDescription();
        
        
        echo "<li>Presupuesto: " . $this->getBudget() . "</li>";
        if ($this->hasChildren()){
            echo "<ul> Workitems: </br>";
            foreach($this->Workitems as $Workitem){
                $Workitem->getDescription();
                echo "</ul>";
            }
            
        } else {
            echo "</ul>";
        }
    }
}

class TimeBasedTask extends AbstractTask {
    private $estimatedHours;
    private $hoursSpent;
    private $ChildTasks = array();

    public function add(AbstractTask $childTask){
        array_push($this->ChildTasks, $childTask);
    }

    public function remove(AbstractTask $childTask){
        array_pop($this->ChildTasks);
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
        return (bool)(count($this->ChildTasks) > 0);
    }

    public function getChild($i) {
        return $this->ChildTasks[$i];
    }

    function __construct($title, $date, $dueDate, $assignedTo, $description, $hoursEstimated, $hoursSpent){
        parent::setTitle($title);
        parent::setDate($date);
        parent::setDueDate($dueDate);
        parent::setAssignedTo($assignedTo);
        parent::setDescription($description);
        $this->setEstimatedHours($hoursEstimated);
        $this->setHoursSpent($hoursSpent);
    }

    public function getDescription(){
        parent::getDescription();

        echo "<li>Horas estimadas: " . $this->getEstimatedHours() . "</li>";
        echo "<li>Horas totales: " . $this->getHoursSpent() . "</li>";

        if ($this->hasChildren()){
            echo "<ul>";
            foreach($this->ChildTasks as $childTask){
                
                $childTask->getDescription();
                echo "</ul>";
            }
            
            } else {
                echo "</ul>";
            }
        }
}

class FixedBudgetTask extends AbstractTask {

    function __construct($title, $date, $dueDate, $assignedTo, $description, $budget){
        parent::setTitle($title);
        parent::setDate($date);
        parent::setDueDate($dueDate);
        parent::setAssignedTo($assignedTo);
        parent::setDescription($description);
        $this->setBudget($budget);
    }

    private $budget;
    private $ChildTasks = array();

    public function add($childTask){
        array_push($this->ChildTasks, $childTask);
    }

    public function remove(){
        array_pop($this->ChildTasks);
    }

    public function hasChildren() {
        return (bool)(count($this->ChildTasks) > 0);
    }

    public function getChild($i) {
        return $this->ChildTasks[$i];
    }

    public function setBudget($budget){
        $this->budget = $budget;
    }

    public function getBudget(){
        return $this->budget;
    }

    public function getDescription(){
        parent::getDescription();
        echo "<li>Presupuesto: " . $this->getBudget() . "</li>";
        if ($this->hasChildren()){
            echo "<ul>";
            foreach($this->ChildTasks as $childTask){
                
                $childTask->getDescription();
                echo "</ul>";
            }
            
        } else {
            echo "</ul>";
        }
    }
}




$timeBasedTask = new TimeBasedTask('Tarea con Tiempo', "2024-01-11", "2024-01-18", "Usuario", "Descripción", 10, 5);
$timeBasedTask2 = new TimeBasedTask("Tarea con Tiempo2", "2024-01-11", "2024-01-18", "Usuario", "Descripción", 11, 6);
$timeBasedTask->add($timeBasedTask2);

$project = new Project("Proyecto Principal", "2024-01-31", "2024-02-01", "Asistente" , "Proyecto 1 desc" , 20);

 $fixedBudgetTask = new FixedBudgetTask("Tarea con Presupuesto", "2024-02-02", "2024-02-03", "Munuera", "Maletín forrado al comité", 200000);
 $subTask = new TimeBasedTask("Tarea con tiempo 3", "2024-01-12", "2024-01-15", "Usuario", "Descripción Subtarea", 8, 3);
 $fixedBudgetTask->add($subTask);
 $project->add($fixedBudgetTask);
 $project->add($timeBasedTask);

// Mostrar la lista de tareas
echo "Lista de tareas:<br>";
$project->getDescription();

?>