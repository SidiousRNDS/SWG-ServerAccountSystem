use swgemu;

-- DELETE test data 
DELETE FROM admin_auth_codes WHERE auth_code="TEST-12345-12346" and username="TestUser";
DELETE FROM admin_auth_codes WHERE auth_code="TEST-12346-12346" and username="dVader";
DELETE FROM admin_auth_codes WHERE auth_code="TEST-12347-12346" and username="lSkywalker";
DELETE FROM admin_auth_codes WHERE auth_code="TEST-12348-12346" and username="bobafett";
