select machine_id , round(avg(processing_time),3) as processing_time from (
    select machine_id ,
    timestamp - lag(timestamp) over (partition by machine_id , process_id order by timestamp ) as processing_time
    from Activity
) as t
group by machine_id;