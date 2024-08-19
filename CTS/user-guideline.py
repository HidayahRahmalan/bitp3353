from fpdf import FPDF

class PDF(FPDF):
    def header(self):
        self.set_font('Arial', 'B', 12)
        self.cell(0, 10, 'PDF Document Title', 0, 1, 'C')

    def footer(self):
        self.set_y(-15)
        self.set_font('Arial', 'I', 8)
        self.cell(0, 10, f'Page {self.page_no()}', 0, 0, 'C')

    def chapter_title(self, title):
        self.set_font('Arial', 'B', 12)
        self.cell(0, 10, title, 0, 1, 'L')
        self.ln(10)

    def chapter_body(self, body):
        self.set_font('Arial', '', 12)
        self.multi_cell(0, 10, body)
        self.ln()

pdf = PDF()
pdf.add_page()
pdf.set_title('User Guideline for FTMK Credit Transfer System (CTS)')
pdf.set_author('Hidayah Burhannudin')
pdf.chapter_body('Welcome to the Credit Transfer System! Please follow these steps to ensure a smooth experience:')


pdf.chapter_title('1. Select Your Role')
pdf.chapter_body('Upon accessing the system, you will be prompted to select your role as either a student or a lecturer.')

pdf.chapter_title('2. Login Credentials:')
pdf.chapter_body('Students: Login using your matriculation number.')
pdf.chapter_body('Lecturers: Login using your staff ID.')

pdf.chapter_title('3. Default Password:')
pdf.chapter_body('Students: Use the default password "pelajarftmk".')
pdf.chapter_body('Lecturers: Use the default password "pensyarahftmk".')

pdf.chapter_title('4. Change Default Password:')
pdf.chapter_body('After logging in for the first time, both students and lecturers are required to change their default password to ensure account security. Follow the on-screen instructions to update your password.')

pdf.chapter_title('5. Login Credentials:')
pdf.chapter_body('If you forget your password, you can reset it by following the "Forgot Password" link on the login page. Note that this option is not available for administrators.')

pdf.chapter_title('6. Get a help:')
pdf.chapter_body('For any issues or further assistance, please contact the system administrator.')

pdf.chapter_body('Thank you for using the Credit Transfer System!')



pdf.output('user-guideline.pdf')
