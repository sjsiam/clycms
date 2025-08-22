@@ .. @@
 -- Media/Attachments table
 CREATE TABLE IF NOT EXISTS media (
     id INT AUTO_INCREMENT PRIMARY KEY,
     filename VARCHAR(255) NOT NULL,
     original_filename VARCHAR(255) NOT NULL,
     file_path VARCHAR(255) NOT NULL,
     file_size INT NOT NULL,
     mime_type VARCHAR(100) NOT NULL,
+    width INT DEFAULT NULL,
+    height INT DEFAULT NULL,
     alt_text VARCHAR(255),
     caption TEXT,
+    description TEXT,
     uploaded_by INT,
     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
     FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL,
     INDEX idx_mime_type (mime_type),
     INDEX idx_uploaded_by (uploaded_by)
 );