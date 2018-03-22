SELECT
a.id
FROM alert a
LEFT JOIN ticket t ON t.id = a.ticket_id
LEFT JOIN client c ON c.id = t.client_id
WHERE
a.sms_sent_at IS NULL
AND c.phone IS NOT NULL
AND t.claim_id IS NULL