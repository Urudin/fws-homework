SELECT
    ppc.product_package_id,
    SUM(ppc.quantity * ph.price) AS package_price
FROM
    product_package_contents ppc
        JOIN
    price_history ph ON ppc.product_id = ph.product_id AND ph.updated_at = (
        SELECT MAX(updated_at)
        FROM price_history
        WHERE product_id = ph.product_id AND updated_at <= '2024-03-30' -- A megadott dátum
    )
WHERE
        ppc.product_package_id = 2 -- A használt packade id
GROUP BY
    ppc.product_package_id;