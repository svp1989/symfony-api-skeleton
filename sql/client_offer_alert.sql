insert into alert(ticket_id)
    select id
    from ticket
    where delay > :ticket_delay
    and id not in (
        select ticket_id
        from alert
    )