select e2.name from Employee as e join Employee as e2 on e.managerId = e2.id
group by e.managerId , e2.name
having count(e.name) > 4 or e2.name is null