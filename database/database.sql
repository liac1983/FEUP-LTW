DROP TABLE IF EXISTS FAQ;
DROP TABLE IF EXISTS Changes;
DROP TABLE IF EXISTS AgentDepartment;
DROP TABLE IF EXISTS TicketHashtag;
DROP TABLE IF EXISTS Hashtag;
DROP TABLE IF EXISTS Department;
DROP TABLE IF EXISTS Admin;
DROP TABLE IF EXISTS Message;
DROP TABLE IF EXISTS Ticket;
DROP TABLE IF EXISTS Agent;
DROP TABLE IF EXISTS Client;


CREATE TABLE Client (
    ClientID INTEGER PRIMARY KEY AUTOINCREMENT,
    Username TEXT NOT NULL UNIQUE,
    Name TEXT NOT NULL,
    Password TEXT NOT NULL,
    Email TEXT NOT NULL,
    Photo TEXT DEFAULT "default.png",
    Role INTEGER NOT NULL DEFAULT 1            
);

CREATE TABLE Agent (
    AgentID INTEGER PRIMARY KEY AUTOINCREMENT,
    ClientID INTEGER NOT NULL,
    FOREIGN KEY(ClientID) REFERENCES Client(ClientID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Admin (
    AdminID INTEGER PRIMARY KEY AUTOINCREMENT,
    AgentID INTEGER NOT NULL,
    FOREIGN KEY(AgentID) REFERENCES Agent(AgentID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Ticket (
    TicketID INTEGER PRIMARY KEY AUTOINCREMENT,
    Title TEXT NOT NULL,
    Description TEXT NOT NULL,
    Date TEXT NOT NULL,
    Status INTEGER DEFAULT 1,
    Priority INTEGER DEFAULT 3,         
    Category TEXT NOT NULL,
    departmentName TEXT NOT NULL DEFAULT "General",
    ClientID INTEGER NOT NULL,
    AgentID INTEGER DEFAULT NULL,
    ClientTrack INTEGER NOT NULL DEFAULT 0,
    AgentTrack INTEGER NOT NULL DEFAULT 0,
    CHECK(ClientTrack >= 0 AND ClientTrack < 2),
    CHECK(AgentTrack >= 0 AND AgentTrack < 2),
    CHECK(Priority > 0 AND Priority < 4),
    CHECK(Status > 0 AND Status < 4),
    FOREIGN KEY(ClientID) REFERENCES Client(ClientID) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(AgentID) REFERENCES Client(ClientID) ON DELETE SET DEFAULT ON UPDATE CASCADE
);

CREATE TABLE Department (
    departmentName TEXT PRIMARY KEY,
    Description TEXT NOT NULL
);

CREATE TABLE Message (
    MessageID INTEGER PRIMARY KEY AUTOINCREMENT,
    TicketID INTEGER NOT NULL,
    Date TEXT NOT NULL,
    ClientID INTEGER NOT NULL,
    Content TEXT NOT NULL,
    FOREIGN KEY(TicketID) REFERENCES Ticket(TicketID) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(ClientID) REFERENCES Client(ClientID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Changes (
    ChangesID INTEGER PRIMARY KEY AUTOINCREMENT,
    Date TEXT NOT NULL,
    Content TEXT NOT NULL,
    Username TEXT NOT NULL,
    TicketID INTEGER NOT NULL,
    FOREIGN KEY (TicketID) REFERENCES Ticket(TicketID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE FAQ (
    FaqID INTEGER PRIMARY KEY AUTOINCREMENT,
    Title TEXT NOT NULL,
    Content TEXT NOT NULL
);

CREATE TABLE Hashtag(
    Name TEXT PRIMARY KEY
);

CREATE TABLE AgentDepartment (
    AgentID INTEGER NOT NULL,
    departmentName TEXT NOT NULL,
    FOREIGN KEY(AgentID) REFERENCES Agent(AgentID) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(departmentName) REFERENCES Department(departmentName) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE TicketHashtag (
    TicketID INTEGER NOT NULL,
    HashtagName TEXT NOT NULL,
    FOREIGN KEY(TicketID) REFERENCES Ticket(TicketID) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(HashtagName) REFERENCES Hashtag(Name) ON DELETE CASCADE ON UPDATE CASCADE
);

/* -------------------------------------------------------------------------------------------------------------- */

PRAGMA FOREIGN_KEYS = ON;

INSERT INTO Client (ClientID, Username, Name, Password, Email) VALUES (1, 'john123', 'John Thompson', 'f61a20da9eaa68a9f06dbc1710b10ef0a67208b2059b1f576af6deac23c215f5', 'john@gmail.com'); --asdfghj
INSERT INTO Client (ClientID, Username, Name, Password, Email) VALUES (2, 'agent', 'Jane Doe', 'd4f0bc5a29de06b510f9aa428f1eedba926012b591fef7a518e776a7c9bd1824', 'jane.doe@email.com'); --agent
INSERT INTO Client (ClientID, Username, Name, Password, Email) VALUES (3, 'bob123', 'Bob Johnson', 'b2c56341cc2b9f8bf898bd7528dd39e641b51c4fbd51f241b46ad70872dd1b99', 'bob.johnson@email.com'); --pass111
INSERT INTO Client (ClientID, Username, Name, Password, Email) VALUES (4, 'alice123', 'Alice Johnson', '0be5449fd7e110e562888c7f6b2ceac607083936e4a8f286fcf9a2d672f73135', 'alice.johnson@email.com'); --pass222
INSERT INTO Client (ClientID, Username, Name, Password, Email) VALUES (5, 'samantha', 'Samantha Brown', '82cc50ae50f2c39014ef7b995bd1050f2638a836a9e0a123088d670dfd5f2ca8', 'samantha.brown@email.com'); --pass333
INSERT INTO Client (ClientID, Username, Name, Password, Email) VALUES (6, 'david123', 'David Wilson', 'ae4b6c9d91b55bc6be8ff9b16057c96a72af85a872cf4505bea9a0b6cff2a65f', 'david.wilson@email.com'); --pass444
INSERT INTO Client (ClientID, Username, Name, Password, Email) VALUES (7, 'admin', 'Jacob Kim', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 'jacob.kim@email.com'); --admin 


INSERT INTO AGENT (AgentID, ClientID) VALUES (1,2);
INSERT INTO AGENT (AgentID, ClientID) VALUES (2,3);
INSERT INTO AGENT (AgentID, ClientID) VALUES (3,5);
INSERT INTO AGENT (AgentID, ClientID) VALUES (4,7);
UPDATE Client SET Role = 2 WHERE ClientID = 2;
UPDATE Client SET Role = 2 WHERE ClientID = 3;
UPDATE Client SET Role = 2 WHERE ClientID = 5;
UPDATE Client SET Role = 2 WHERE ClientID = 7;

INSERT INTO ADMIN (AdminID, AgentID) VALUES (1,4);
UPDATE Client SET Role = 3 WHERE ClientID = 7;


INSERT INTO Ticket (TicketID, Title, Description, Date, Status, Priority, Category, departmentName, ClientID, AgentID) VALUES 
(1, 'Issue with payment processing', 'I am unable to process my payment. Please help me resolve this issue as soon as possible.', '2023-04-18', 1, 1, 'Payment', 'Finance', 2, 3);
INSERT INTO Ticket (TicketID, Title, Description, Date, Status, Priority, Category, departmentName, ClientID, AgentID) VALUES 
(2, 'Unable to access my account', 'I am unable to access my account. It says that my username or password is incorrect.', '2023-04-18', 1, 2, 'Account Access', 'IT', 1, 4);
INSERT INTO Ticket (TicketID, Title, Description, Date, Status, Priority, Category, departmentName, ClientID, AgentID) VALUES 
(3, 'Issue with delivery of my order', 'I have not received my order yet. Please let me know when I can expect to receive it.', '2023-04-18', 1, 3, 'Delivery', 'Logistics', 5, 1);
INSERT INTO Ticket (TicketID, Title, Description, Date, Status, Priority, Category, departmentName, ClientID, AgentID) VALUES 
(4, 'Product not as described', 'The product I received is not as described on your website. Please let me know how this can be resolved.', '2023-04-18', 1, 2, 'Product Description', 'Customer Service', 6, 3);
INSERT INTO Ticket (TicketID, Title, Description, Date, Status, Priority, Category, departmentName, ClientID, AgentID) VALUES 
(5, 'Issue with website loading', 'I am having trouble loading your website. It seems to be taking a long time to load.', '2023-04-18', 1, 1, 'Website', 'IT', 4, 2);

INSERT INTO Department (departmentName, Description) VALUES ('General', '');
INSERT INTO Department (departmentName, Description) VALUES ('Marketing', "The Marketing department is responsible for promoting an organization's products or services to its target customers. This includes developing marketing strategies and plans, creating and executing advertising campaigns, and conducting market research to understand consumer needs and preferences. Marketing professionals in this department may have a wide range of roles, including market researchers, copywriters, graphic designers, public relations specialists, and social media managers. They are critical to driving sales and revenue for the organization, by creating awareness and interest in its products or services. The Marketing department is often closely aligned with the Sales department, working together to identify and pursue new business opportunities.");
INSERT INTO Department (departmentName, Description) VALUES ('Human Resources', "The Human Resources department is responsible for managing an organization's workforce. This includes recruiting and hiring new employees, onboarding and training them, managing employee benefits and compensation, and maintaining employee records. HR professionals in this department may have a wide range of roles, including recruiters, benefits coordinators, payroll specialists, and employee relations managers. They are critical to ensuring that an organization's employees are engaged, motivated, and supported throughout their employment. The HR department is also responsible for ensuring that the organization complies with labor laws and regulations, and for developing and implementing policies and procedures to promote fairness, equity, and diversity in the workplace.");
INSERT INTO Department (departmentName, Description) VALUES ('Finance', "The Finance department is responsible for managing an organization's financial resources and ensuring the financial health and stability of the company. This department is involved in various financial activities such as financial planning, budgeting, forecasting, and reporting. The Finance department plays a crucial role in monitoring and analyzing the organization's financial performance, managing cash flow, and making strategic financial decisions. Finance professionals in this department may have a wide range of roles, including financial analysts, accountants, controllers, and financial managers. They work closely with other departments to provide financial insights, support decision-making, and optimize the allocation of financial resources.");
INSERT INTO Department (departmentName, Description) VALUES ('IT', "The IT department, short for Information Technology department, is responsible for managing and maintaining an organization's technology infrastructure. This includes computer hardware and software, networking and telecommunications systems, and other digital assets. The IT department is often responsible for developing and implementing technology strategies and policies to improve the organization's productivity, security, and efficiency. IT professionals in this department may have a wide range of roles, including network administrators, software developers, data analysts, and technical support specialists. They are critical to ensuring that an organization's digital systems are reliable, secure, and meet the needs of the business.");
INSERT INTO Department (departmentName, Description) VALUES ('Sales', "The Sales department is responsible for generating revenue for the organization by selling its products or services to customers. This department plays a crucial role in identifying and pursuing new business opportunities, building relationships with customers, and achieving sales targets. Sales professionals in this department may have a wide range of roles, including sales representatives, account managers, sales managers, and business development executives. They are responsible for developing and executing sales strategies, identifying potential customers, conducting market research, and negotiating contracts and deals. The Sales department works closely with other departments such as Marketing, Customer Service, and Operations to ensure that the products or services meet customer needs and are delivered on time and on budget. Sales professionals also work to build long-term relationships with customers, provide excellent customer service, and address any issues or concerns that may arise.");
INSERT INTO Department (departmentName, Description) VALUES ('Customer Support', "The Customer Support department is responsible for providing assistance and support to customers who have questions or issues with an organization's products or services. This department plays a critical role in building and maintaining customer loyalty, by providing timely and effective resolution of customer inquiries and concerns. Customer support professionals in this department may have a wide range of roles, including customer service representatives, technical support specialists, and help desk technicians. They are responsible for responding to customer inquiries, troubleshooting technical issues, and providing guidance on product usage. The Customer Support department works closely with other departments such as Sales, Marketing, and Product Development to ensure that customer needs are being met and that products or services are of the highest quality. They may also be responsible for identifying patterns and trends in customer inquiries and feedback, and providing insights to other departments to help improve the overall customer experience.");
INSERT INTO Department (departmentName, Description) VALUES ('Logistics', "The Logistics department is responsible for managing the movement and storage of goods and materials throughout an organization's supply chain. This department plays a critical role in ensuring that products are delivered to customers on time, at the right location, and at the lowest possible cost. Logistics professionals in this department may have a wide range of roles, including supply chain managers, transportation planners, warehouse managers, and distribution coordinators. They are responsible for coordinating the movement of goods from suppliers to manufacturers, from manufacturers to distributors, and from distributors to customers.");


INSERT INTO Message (MessageID, TicketID, Date, ClientID, Content) VALUES (1, 1, '2023-05-05 23:21', 2, 'I apologize for the inconvenience. Can you provide more details about the issue you are experiencing with Product X?');
INSERT INTO Message (MessageID, TicketID, Date, ClientID, Content) VALUES (2, 1, '2023-05-05 23:21', 3, 'Thank you for the information. We are looking into the issue and will get back to you as soon as possible.');
INSERT INTO Message (MessageID, TicketID, Date, ClientID, Content) VALUES (3, 1, '2023-05-05 23:21', 2, 'I am also experiencing this issue.');
INSERT INTO Message (MessageID, TicketID, Date, ClientID, Content) VALUES (4, 2, '2023-05-05 23:21', 3, 'Thank you for contacting us. We will respond to your inquiry as soon as possible.');
INSERT INTO Message (MessageID, TicketID, Date, ClientID, Content) VALUES (5, 3, '2023-05-05 23:21', 1, 'I apologize for the delay. Our shipping department is experiencing a backlog of orders. We expect your product to ship in the next 3-5 business days.');
INSERT INTO Message (MessageID, TicketID, Date, ClientID, Content) VALUES (6, 4, '2023-05-05 23:21', 2, 'I can assist you with resetting your password. Please provide me with your username and email address.');

INSERT INTO Changes (ChangesID, Date, Content, Username, TicketID) VALUES (1, '2023-05-05 19:54', 'Ticket created', 'agent', 1);
INSERT INTO Changes (ChangesID, Date, Content, Username, TicketID) VALUES (2, '2023-05-05 19:54', 'Assigned to agent', 'samantha', 1);
INSERT INTO Changes (ChangesID, Date, Content, Username, TicketID) VALUES (3, '2023-05-06 19:54', 'Ticket created', 'john123', 2);


INSERT INTO FAQ (FaqID, Title, Content) VALUES (1, 'How do I create a new ticket?', 'To create a new ticket, log into your account and click on the "New Ticket" button.');
INSERT INTO FAQ (FaqID, Title, Content) VALUES (2, 'How can I track the status of my ticket?', 'You can track the status of your ticket by logging into your account and viewing your ticket history.');
INSERT INTO FAQ (FaqID, Title, Content) VALUES (3, 'What information should I include in my ticket?', 'Please include a clear description of the issue, any relevant screenshots or error messages, and any steps you have taken to resolve the issue.');
INSERT INTO FAQ (FaqID, Title, Content) VALUES (4, 'How long does it take to receive a response to my ticket?', 'Our customer support team typically responds to tickets within 24-48 hours.');
INSERT INTO FAQ (FaqID, Title, Content) VALUES (5, 'How do I edit or delete a ticket?', 'You can edit or delete a ticket by navigating to the ticket in your account and clicking on the appropriate button.');
INSERT INTO FAQ (FaqID, Title, Content) VALUES (6, 'What is the difference between "priority" and "status" on a ticket?', 'Priority refers to the level of urgency for the issue, while status refers to the current stage of the ticket resolution process.');
INSERT INTO FAQ (FaqID, Title, Content) VALUES (7, 'How can I view the history of a ticket?', 'You can view the history of a ticket by navigating to the ticket in your account and clicking on the "View History" button.');
INSERT INTO FAQ (FaqID, Title, Content) VALUES (8, 'What should I do if I am not satisfied with the resolution of my ticket?', 'If you are not satisfied with the resolution of your ticket, please contact our customer support team and we will work with you to find a satisfactory solution.');
INSERT INTO FAQ (FaqID, Title, Content) VALUES (9, 'How can I provide feedback on my experience with your customer support team?', 'We welcome your feedback on your experience with our customer support team. Please contact us with any comments or suggestions you may have.');


INSERT INTO Hashtag (Name) VALUES ('#tech');
INSERT INTO Hashtag (Name) VALUES ('#marketing');
INSERT INTO Hashtag (Name) VALUES ('#sales');
INSERT INTO Hashtag (Name) VALUES ('#finance');
INSERT INTO Hashtag (Name) VALUES ('#productivity');
INSERT INTO Hashtag (Name) VALUES ('#customer-service');
INSERT INTO Hashtag (Name) VALUES ('#work-life-balance');
INSERT INTO Hashtag (Name) VALUES ('#leadership');
INSERT INTO Hashtag (Name) VALUES ('#remote-work');
INSERT INTO Hashtag (Name) VALUES ('#teamwork');



INSERT INTO AgentDepartment (AgentID, departmentName) VALUES (1, "Human Resources");
INSERT INTO AgentDepartment (AgentID, departmentName) VALUES (2, "IT");
INSERT INTO AgentDepartment (AgentID, departmentName) VALUES (3, "Marketing");


INSERT INTO TicketHashtag (TicketID, HashtagName) VALUES (1, "#productivity");
INSERT INTO TicketHashtag (TicketID, HashtagName) VALUES (2, "#customer-service");
INSERT INTO TicketHashtag (TicketID, HashtagName) VALUES (3, "#teamwork");
INSERT INTO TicketHashtag (TicketID, HashtagName) VALUES (4, "#leadership");
INSERT INTO TicketHashtag (TicketID, HashtagName) VALUES (5, "#tech");


