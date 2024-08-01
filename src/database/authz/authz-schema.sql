CREATE TABLE authz__account (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE authz__role (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    code_name VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(1000) DEFAULT '' NOT NULL,
    PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE authz__role_state (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    role_id INT UNSIGNED NOT NULL,
    code_name VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT authz__role_state___role_id FOREIGN KEY (role_id) REFERENCES authz__role(id)
) ENGINE = InnoDB;

CREATE TABLE authz__account_role (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    account_id INT UNSIGNED NOT NULL,
    role_id INT UNSIGNED NOT NULL,
    role_state_id INT UNSIGNED NOT NULL,
    `order` SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT authz__account_role___account_id FOREIGN KEY (account_id) REFERENCES authz__account(id),
    CONSTRAINT authz__account_role___role_id    FOREIGN KEY (role_id)    REFERENCES authz__role(id),
    CONSTRAINT authz__account_role___role_state_id    FOREIGN KEY (role_state_id) REFERENCES authz__role_state(id)
) ENGINE = InnoDB;

CREATE TABLE authz__group (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    role_id INT UNSIGNED NULL,
    role_state_id INT UNSIGNED NULL,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(1000) DEFAULT '' NOT NULL,
    options JSON NOT NULL,
    prio SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT authz__group___role_id FOREIGN KEY (role_id) REFERENCES authz__role(id),
    CONSTRAINT authz__group___role_state_id FOREIGN KEY (role_state_id) REFERENCES authz__role_state(id)
) ENGINE = InnoDB;

CREATE TABLE authz__account_group (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    account_id INT UNSIGNED NOT NULL,
    group_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT authz__group_account___account_id FOREIGN KEY (account_id) REFERENCES authz__account(id),
    CONSTRAINT authz__group_account___group_id FOREIGN KEY (group_id) REFERENCES authz__group(id)
) ENGINE = InnoDB;


CREATE TABLE authz__action (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    code_name VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    resource_type VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
) ENGINE = InnoDB;


CREATE TABLE authz__permission (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    action_id INT UNSIGNED NOT NULL,
    resource_code_name_mask VARCHAR (100) NULL,
    PRIMARY KEY (id),
    CONSTRAINT authz__permission___action_id FOREIGN KEY (action_id) REFERENCES authz__action(id)
) ENGINE = InnoDB;

CREATE TABLE authz__role_permission (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    role_id INT UNSIGNED NOT NULL,
    role_state_id INT UNSIGNED NULL,
    permission_id INT UNSIGNED NOT NULL,
    permission ENUM ('ALLOW', 'PROHIBIT') NOT NULL,
    prio SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
    options JSON NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT authz__role_permission___permission_id FOREIGN KEY (permission_id) REFERENCES authz__permission(id),
    CONSTRAINT authz__role_permission___role_id FOREIGN KEY (role_id) REFERENCES authz__role(id),
    CONSTRAINT authz__role_permission___role_state_id FOREIGN KEY (role_state_id) REFERENCES authz__role_state(id)
) ENGINE = InnoDB;

CREATE TABLE authz__group_permission (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    group_id INT UNSIGNED NOT NULL,
    permission_id INT UNSIGNED NOT NULL,
    permission ENUM ('ALLOW', 'PROHIBIT') NOT NULL,
    prio SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT authz__group_permission___permission_id FOREIGN KEY (permission_id) REFERENCES authz__permission(id),
    CONSTRAINT authz__group_permission___group_id FOREIGN KEY (group_id) REFERENCES authz__group(id)
) ENGINE = InnoDB;
