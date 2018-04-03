use swgemu;

-- Insert test data 
INSERT INTO admin_auth_codes SET auth_code="TEST-12345-12346", username="TestUser", email="testuser@test.com", auth_code_used=0;
INSERT INTO admin_auth_codes SET auth_code="TEST-12346-12346", username="dVader", email="dVader@empire.com", auth_code_used=1, used_date="2018-03-01 12:00:00";
INSERT INTO admin_auth_codes SET auth_code="TEST-12347-12346", username="lSkywalker", email="lskywalker@rebels.com", auth_code_used=0;
INSERT INTO admin_auth_codes SET auth_code="TEST-12348-12346", username="bobafett", email="bfett@mandalore.com", auth_code_used=0;
