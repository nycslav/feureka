-- FEureka seed data

USE feureka;

-- Predefined categories for found_items and missing_reports
INSERT INTO categories (category_id, category_name) VALUES
    (1, 'Electronics'),
    (2, 'Documents & IDs'),
    (3, 'Wallets & Purses'),
    (4, 'Keys'),
    (5, 'Clothing'),
    (6, 'Bags'),
    (7, 'Accessories'),
    (8, 'School Supplies'),
    (9, 'Personal Items'),
    (10, 'Others');

-- Registered users
-- Passwords for local testing:
-- Mark Andaya (Admin): Admin1@123
-- Alyssa Santos: Student1@123
-- Miguel Reyes: Student2@123
-- Sofia Cruz: Student3@123
-- Mark Dela Cruz: Staff1@123
INSERT INTO users (
    user_id,
    first_name,
    last_name,
    email,
    password_hash,
    role,
    user_type,
    year_level,
    expiration_date
) VALUES
    (1, 'Mark', 'Andaya', 'mark.andaya.admin@fit.edu.ph', '$2y$10$aBXDHL4Z22XBEM0wBg/22eLes1po1QBh00IFWQmmmNy4Pea1fdUOq', 'Admin', NULL, NULL, NULL),
    (2, 'Celina', 'Espinola', 'celina.espinola@fit.edu.ph', '$2y$12$yTb7dlmUrthX1qPbFyc5dekydS3phbgl0ZO2xBFgvxWAkLP3bcHve', 'User', 'Student', 1, '2030-06-30'),
    (3, 'Syril', 'Celis', 'syril.celis@fit.edu.ph', '$2y$12$hLtF6.1lRsU7vfAYx0hQfO2t66fhDtaD6Vuk8aXnRk/afHXWU9oam', 'User', 'Student', 2, '2029-06-30'),
    (4, 'Sofia', 'Cruz', 'sofia.cruz@fit.edu.ph', '$2y$12$xmQn2dtlsqYWgLUOKSstleXkARZlcf0WkIPIQkdUJ705uVPkeutT.', 'User', 'Student', 3, '2028-06-30'),
    (5, 'Mark', 'Dela Cruz', 'mark.delacruz@fit.edu.ph', '$2y$10$yFVtSiuWtZUBxCivWHfZCukyL6MdNopFjgQDXduPzAkaA8ekejm.y', 'User', 'Staff', NULL, NULL);

-- Sample found items
INSERT INTO found_items (
    user_id,
    category_id,
    item_name,
    item_description,
    room,
    floor,
    location_description,
    date_found,
    status,
    image,
    processed_by,
    admin_notes
) VALUES
    (
        2,
        1,
        'Black Wireless Earbuds',
        'Black wireless earbuds in a small charging case.',
        'Room 402',
        '4th Floor',
        'Found on the teacher''s desk after the afternoon class.',
        '2026-07-05',
        'Pending',
        'assets/uploads/found-black-wireless-earbuds.jpg',
        NULL,
        NULL
    ),
    (
        3,
        2,
        'Student ID Lanyard',
        'FEU student ID green lanyard',
        'Library',
        '14th Floor',
        'Found near the computer area.',
        '2026-07-03',
        'Approved',
        'assets/uploads/found-student-id-lanyard.jpg',
        1,
        'Approved for display after checking the submitted details.'
    ),
    (
        4,
        6,
        'Navy Blue Backpack',
        'Navy blue backpack with two notebooks inside.',
        'Cafeteria',
        'Ground Floor',
        'Found under a table near the cashier area.',
        '2026-06-29',
        'Under Review',
        'assets/uploads/found-navy-blue-backpack.jpg',
        1,
        'Possible owner advised to visit the Lost and Found Office for verification.'
    ),
    (
        5,
        4,
        'Silver Key Ring With Red Tag',
        'Small silver key ring with three keys and a red tag.',
        'Room 605',
        '6th Floor',
        'Found beside the front row seats.',
        '2026-06-18',
        'Claimed',
        'assets/uploads/found-silver-key-ring-with-red-tag.jpg',
        1,
        'Released after physical ownership verification at the Lost and Found Office.'
    ),
    (
        5,
        8,
        'FX-991MS Scientific Calculator',
        'Gray scientific calculator.',
        'Room 1104',
        '11th Floor',
        'Reported from the back row of the classroom.',
        '2026-06-24',
        'Pending',
        'assets/uploads/found-fx-991ms-scientific-calculator.jpg',
        NULL,
        NULL
    );

-- Sample missing reports
INSERT INTO missing_reports (
    user_id,
    category_id,
    item_name,
    item_description,
    room,
    floor,
    location_description,
    date_lost,
    contact_number,
    status,
    image,
    processed_by,
    admin_notes
) VALUES
    (
        2,
        1,
        'Gray FX-991MS Scientific Calculator',
        'Gray FX-991MS scientific calculator without calculator lid.',
        'Room 1104',
        '11th Floor',
        'Last used during a morning class.',
        '2026-07-02',
        '0917-555-0101',
        'Open',
        NULL,
        NULL,
        NULL
    ),
    (
        3,
        3,
        'Brown Wallet',
        'Brown leather wallet containing school receipts.',
        'Canteen',
        '8th Floor',
        'Possibly left on a dining table after lunch.',
        '2026-06-27',
        '0917-555-0102',
        'Possible Match',
        'assets/uploads/missing-brown-wallet.jpg',
        1,
        'Potential match found; owner should visit the Lost and Found Office.'
    ),
    (
        5,
        2,
        'White Spiral Notebook',
        'White spiral notebook with handwritten lecture notes.',
        'iCare',
        '14th Floor',
        'Last seen at a cubicle desk',
        '2026-06-20',
        '0917-555-0103',
        'Resolved',
        'assets/uploads/missing-white-spiral-notebook.jpg',
        1,
        'Resolved after ownership was verified physically at the Lost and Found Office.'
    );
