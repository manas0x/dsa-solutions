with left_manager as (
select employee_id as id from Employees
)
select employee_id from Employees
where salary < 30000 and manager_id not in (select id from left_manager)
order by employee_id